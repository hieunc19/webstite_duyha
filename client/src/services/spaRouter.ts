/**
 * SPA Client-Side Router for Duy Ha Portal
 * Provides instantaneous 0ms soft navigation between all portal subpages and homepage.
 * Keeps Header, Footer, Weather widget, and Theme state completely uninterrupted.
 */

import { initSharedHeader } from '../components/sharedHeader';
import { initSubpageBanners } from '../components/sharedSubpageBanner';

interface CacheEntry {
  html: string;
  timestamp: number;
}

// In-memory RAM cache for instant 0ms transitions (TTL: 60 seconds)
const pageCache = new Map<string, CacheEntry>();
const CACHE_TTL_MS = 60 * 1000;

// Callback hooks for page transitions
type NavigationCallback = (url: string) => void;
const navigationHooks: NavigationCallback[] = [];

export function onSpaNavigate(callback: NavigationCallback): () => void {
  navigationHooks.push(callback);
  return () => {
    const idx = navigationHooks.indexOf(callback);
    if (idx !== -1) navigationHooks.splice(idx, 1);
  };
}

/**
 * Check if a URL is eligible for SPA routing
 */
export function isInternalPortalUrl(url: string): boolean {
  try {
    const parsed = new URL(url, window.location.origin);

    // Must be same origin
    if (parsed.origin !== window.location.origin) return false;

    const path = parsed.pathname;

    // Exclude admin, api, storage, direct assets
    if (path.startsWith('/admin') || path.startsWith('/api') || path.startsWith('/storage')) {
      return false;
    }

    // Exclude static assets with extensions other than html
    if (/\.(jpg|jpeg|png|gif|svg|webp|ico|css|js|json|pdf|docx|xlsx|zip|mp4|webm)$/i.test(path)) {
      return false;
    }

    return true;
  } catch {
    return false;
  }
}

/**
 * Normalize URL for cache key
 */
function normalizeUrl(url: string): string {
  try {
    const parsed = new URL(url, window.location.origin);
    return parsed.pathname + parsed.search;
  } catch {
    return url;
  }
}

/**
 * Preload an internal page in background cache
 */
export async function preloadPage(url: string): Promise<string | null> {
  if (!isInternalPortalUrl(url)) return null;

  const key = normalizeUrl(url);
  const now = Date.now();
  const cached = pageCache.get(key);

  if (cached && now - cached.timestamp < CACHE_TTL_MS) {
    return cached.html;
  }

  try {
    const res = await fetch(url, {
      headers: { 'X-Requested-With': 'SpaRouter' }
    });
    if (!res.ok) return null;
    const html = await res.text();
    pageCache.set(key, { html, timestamp: Date.now() });
    return html;
  } catch {
    return null;
  }
}

/**
 * Clear in-memory page cache (e.g., when Admin changes are detected or on demand)
 */
export function clearSpaCache(): void {
  pageCache.clear();
}

/**
 * Execute script elements found in the newly loaded HTML
 */
function executePageScripts(doc: Document): void {
  const scripts = doc.querySelectorAll('script');
  scripts.forEach(script => {
    const src = script.getAttribute('src');

    // Skip main.ts/main.js as it is already running in current environment
    if (src && (src.includes('main.ts') || src.includes('main.js') || src.includes('cdn.tailwindcss.com') || src.includes('driver.js'))) {
      return;
    }

    const newScript = document.createElement('script');
    Array.from(script.attributes).forEach(attr => {
      newScript.setAttribute(attr.name, attr.value);
    });

    if (script.textContent) {
      newScript.textContent = script.textContent;
    }

    document.body.appendChild(newScript);
    // Cleanup temporary inline script tag after execution
    setTimeout(() => {
      if (newScript.parentNode) {
        newScript.parentNode.removeChild(newScript);
      }
    }, 100);
  });
}

/**
 * Perform smooth client-side transition to target URL
 */
export async function navigateTo(url: string, pushState: boolean = true): Promise<boolean> {
  if (!isInternalPortalUrl(url)) {
    window.location.href = url;
    return false;
  }

  const targetUrl = new URL(url, window.location.origin);
  const currentUrl = new URL(window.location.href);

  // If navigating to the exact same page with just a hash change, handle hash scroll
  if (targetUrl.pathname === currentUrl.pathname && targetUrl.search === currentUrl.search) {
    if (targetUrl.hash) {
      if (pushState) history.pushState({ url }, '', targetUrl.href);
      const targetElem = document.querySelector(targetUrl.hash);
      if (targetElem) {
        targetElem.scrollIntoView({ behavior: 'smooth' });
      }
    }
    return true;
  }

  // Visual indication / Fade out current main
  const currentMain = document.querySelector('main') || document.getElementById('portal-main-view');
  if (currentMain) {
    currentMain.style.transition = 'opacity 0.1s ease-out';
    currentMain.style.opacity = '0.3';
  }

  let html = await preloadPage(url);
  if (!html) {
    // Fallback to normal navigation if fetch fails
    window.location.href = url;
    return false;
  }

  try {
    const parser = new DOMParser();
    const newDoc = parser.parseFromString(html, 'text/html');

    // 1. Update Document Title
    const newTitle = newDoc.querySelector('title')?.textContent;
    if (newTitle) {
      document.title = newTitle;
    }

    // 2. Update Meta Description
    const newDesc = newDoc.querySelector('meta[name="description"]')?.getAttribute('content');
    if (newDesc) {
      let descElem = document.querySelector('meta[name="description"]');
      if (descElem) descElem.setAttribute('content', newDesc);
    }

    // 3. Swap Main Content
    const newMain = newDoc.querySelector('main') || newDoc.getElementById('portal-main-view');
    if (currentMain && newMain) {
      // Replace main outerHTML
      currentMain.outerHTML = newMain.outerHTML;
    } else if (newMain) {
      // If current main was not found, insert after header
      const header = document.getElementById('app-header');
      if (header && header.nextSibling) {
        header.parentNode?.insertBefore(newMain, header.nextSibling);
      } else {
        document.body.appendChild(newMain);
      }
    }

    // 4. Synchronize any page-specific modals outside <main> (e.g. #all-officials-modal, #image-preview-modal)
    const newModals = newDoc.querySelectorAll('[id$="-modal"]');
    newModals.forEach(modal => {
      const existing = document.getElementById(modal.id);
      if (existing) {
        existing.outerHTML = modal.outerHTML;
      } else {
        document.body.appendChild(modal.cloneNode(true));
      }
    });

    // 5. Update browser history
    if (pushState) {
      history.pushState({ url }, '', targetUrl.href);
    }

    // 6. Scroll to top or target hash
    if (targetUrl.hash) {
      setTimeout(() => {
        const hashElem = document.querySelector(targetUrl.hash);
        if (hashElem) hashElem.scrollIntoView({ behavior: 'smooth' });
      }, 50);
    } else {
      window.scrollTo({ top: 0, behavior: 'instant' });
    }

    // 7. Update Header active states & Subpage Banners
    initSharedHeader();
    initSubpageBanners();

    // 8. Execute page scripts
    executePageScripts(newDoc);

    // 9. Fire lifecycle events and notify subscribers
    document.dispatchEvent(new Event('DOMContentLoaded'));
    window.dispatchEvent(new CustomEvent('spa:navigated', { detail: { url: targetUrl.href, pathname: targetUrl.pathname } }));
    navigationHooks.forEach(hook => {
      try { hook(targetUrl.href); } catch (e) { console.warn('SPA navigation hook error:', e); }
    });

    // 10. Reveal fresh content smoothly
    document.body.classList.add('js-hydrated');
    const updatedMain = document.querySelector('main') || document.getElementById('portal-main-view');
    if (updatedMain) {
      updatedMain.style.opacity = '1';
    }

    // Close mobile nav menu if open
    const mobileMenu = document.getElementById('mobile-nav-menu');
    if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
      mobileMenu.classList.add('hidden');
    }

    return true;
  } catch (err) {
    console.error('SPA Navigation error, falling back to full reload:', err);
    window.location.href = url;
    return false;
  }
}

/**
 * Initialize SPA Global Click Interceptor and Popstate Listener
 */
export function initSpaRouter(): void {
  // Prevent duplicate initialization
  if ((window as any).__SPA_ROUTER_INITIALIZED__) return;
  (window as any).__SPA_ROUTER_INITIALIZED__ = true;

  // Intercept all link clicks on document
  document.addEventListener('click', (e: MouseEvent) => {
    // Only handle primary left click without modifier keys
    if (e.button !== 0 || e.ctrlKey || e.metaKey || e.altKey || e.shiftKey) return;

    // Find closest anchor tag
    const target = e.target as HTMLElement | null;
    const anchor = target?.closest('a') as HTMLAnchorElement | null;
    if (!anchor) return;

    // Check conditions to ignore
    if (anchor.hasAttribute('download')) return;
    if (anchor.getAttribute('target') === '_blank') return;
    if (anchor.hasAttribute('data-no-spa')) return;
    if (anchor.getAttribute('rel') === 'external') return;

    const href = anchor.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) {
      return;
    }

    // Check if internal URL
    if (isInternalPortalUrl(anchor.href)) {
      e.preventDefault();
      navigateTo(anchor.href, true);
    }
  });

  // Hover & touch prefetching for instant 0ms transition
  document.addEventListener('pointerenter', (e: PointerEvent) => {
    const target = e.target as HTMLElement | null;
    const anchor = target?.closest('a') as HTMLAnchorElement | null;
    if (!anchor) return;

    const href = anchor.getAttribute('href');
    if (href && isInternalPortalUrl(anchor.href) && !anchor.hasAttribute('data-no-spa')) {
      preloadPage(anchor.href);
    }
  }, { capture: true, passive: true });

  document.addEventListener('touchstart', (e: TouchEvent) => {
    const target = e.target as HTMLElement | null;
    const anchor = target?.closest('a') as HTMLAnchorElement | null;
    if (!anchor) return;

    const href = anchor.getAttribute('href');
    if (href && isInternalPortalUrl(anchor.href) && !anchor.hasAttribute('data-no-spa')) {
      preloadPage(anchor.href);
    }
  }, { capture: true, passive: true });

  // Handle browser Back & Forward buttons
  window.addEventListener('popstate', () => {
    navigateTo(window.location.href, false);
  });

  // Invalidate cache on window focus to guarantee fresh admin data
  window.addEventListener('focus', () => {
    clearSpaCache();
  });

  console.log('⚡ SPA Client-Side Router initialized successfully.');
}
