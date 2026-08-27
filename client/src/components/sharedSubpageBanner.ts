import subpageBannersData from '../data/subpage_banners.json';

export interface SubpageBannerItem {
  page_code: string;
  page_name: string;
  page_url: string;
  badge_text: string;
  badge_icon: string;
  title: string;
  subtitle: string;
  bg_image: string;
}

export type SubpageBannersMap = Record<string, SubpageBannerItem>;

let activeSubpageBanners: SubpageBannersMap = (subpageBannersData as unknown) as SubpageBannersMap;

/**
 * Resolve a raw bg_image path to a full URL
 */
function resolveBannerUrl(rawUrl: string | null | undefined): string {
  if (!rawUrl) return '/hero-bg.jpg';
  if (rawUrl.startsWith('http://') || rawUrl.startsWith('https://')) return rawUrl;
  if (rawUrl.startsWith('/storage/') || rawUrl.startsWith('/')) return rawUrl;
  return '/storage/' + rawUrl;
}

/**
 * Determine current page key from URL pathname + search params.
 * Works at any point — no DOM needed.
 */
export function getCurrentPageKey(): string | null {
  const path = window.location.pathname.toLowerCase();
  const search = window.location.search.toLowerCase();

  // Handle unified procedures.html with 3 tabs
  if (path.includes('procedures') || path.includes('video-guides') || path.includes('policies')) {
    if (search.includes('tab=videos') || path.includes('video-guides')) return 'video_guides';
    if (search.includes('tab=policies') || path.includes('policies')) return 'policies';
    return 'procedures';
  }

  if (path.includes('officials')) return 'officials';
  if (path.includes('tdp-merger') || path.includes('tdp_merger')) return 'tdp_merger';
  if (path.includes('meritorious-families') || path.includes('meritorious_families')) return 'meritorious_families';
  if (path.includes('agencies')) return 'agencies';
  if (path.includes('waste-schedule') || path.includes('waste_schedule')) return 'waste_schedule';
  if (path.includes('forms')) return 'forms';
  if (path.includes('feedback')) return 'feedback';
  if (path.includes('citizen-reception') || path.includes('citizen_reception')) return 'citizen_reception';

  return null;
}

// ─── EARLY PREFETCH: Start downloading the banner image ASAP (at module-load time) ───
// This runs as soon as the JS bundle is parsed, BEFORE DOMContentLoaded.
// By the time renderCurrentSubpageBanner() runs, the image is already cached.


(function prefetchBannerImage(): void {
  const pageKey = getCurrentPageKey();
  if (!pageKey) return;

  const bannerCfg = activeSubpageBanners[pageKey];
  if (!bannerCfg?.bg_image) return;

  const bgUrl = resolveBannerUrl(bannerCfg.bg_image);

  // Strategy 1: Inject <link rel="preload"> into <head> for highest priority
  if (!document.querySelector(`link[rel="preload"][href="${bgUrl}"]`)) {
    const link = document.createElement('link');
    link.rel = 'preload';
    link.as = 'image';
    link.href = bgUrl;
    link.setAttribute('fetchpriority', 'high');
    document.head.appendChild(link);
  }

  // Strategy 2: Start Image() download in parallel (works even before DOM is ready)
  const img = new Image();
  img.fetchPriority = 'high';
  img.src = bgUrl;
})();

export function applySubpageBannersConfig(data: SubpageBannersMap): void {
  if (data && typeof data === 'object') {
    activeSubpageBanners = data;
    renderCurrentSubpageBanner();
  }
}

(window as any).renderCurrentSubpageBanner = renderCurrentSubpageBanner;

export function renderCurrentSubpageBanner(): void {
  const pageKey = getCurrentPageKey();
  if (!pageKey) return;

  const bannerCfg = activeSubpageBanners[pageKey];
  if (!bannerCfg) return;

  // Find banner element
  const bannerEl = document.getElementById('subpage-hero-banner') ||
    document.querySelector('[data-subpage]') as HTMLElement ||
    document.querySelector('.subpage-hero-banner') as HTMLElement ||
    document.querySelector('main > div[style*="background-image"]') as HTMLElement ||
    document.querySelector('main > div.bg-cover') as HTMLElement ||
    document.querySelector('main > div.rounded-3xl') as HTMLElement;

  if (!bannerEl) return;

  // Apply background image (likely already cached from prefetch)
  const bgUrl = resolveBannerUrl(bannerCfg.bg_image);
  bannerEl.style.backgroundImage = `url('${bgUrl}')`;

  // Apply Badge
  const badgeEl = document.getElementById('subpage-banner-badge') ||
    bannerEl.querySelector('.subpage-badge') ||
    bannerEl.querySelector('[class*="rounded-full"]');
  if (badgeEl && bannerCfg.badge_text) {
    const iconHtml = bannerCfg.badge_icon ? `<span class="material-symbols-outlined text-sm">${bannerCfg.badge_icon}</span>` : '';
    badgeEl.innerHTML = `${iconHtml}<span>${bannerCfg.badge_text}</span>`;
  }

  // Lighten / soften overlay so background image is bright and crisp
  const overlayEl = bannerEl.querySelector('.absolute.inset-0') as HTMLElement;
  if (overlayEl) {
    overlayEl.className = 'absolute inset-0 bg-gradient-to-r from-black/40 via-black/15 to-transparent z-0 pointer-events-none';
  }

  // Apply Title
  const titleEl = document.getElementById('subpage-banner-title') ||
    bannerEl.querySelector('h2') ||
    bannerEl.querySelector('h1');
  if (titleEl && bannerCfg.title) {
    titleEl.textContent = bannerCfg.title;
    (titleEl as HTMLElement).style.textShadow = '0 2px 8px rgba(0,0,0,0.85), 0 4px 16px rgba(0,0,0,0.6)';
  }

  // Apply Subtitle
  const subtitleEl = document.getElementById('subpage-banner-subtitle') ||
    bannerEl.querySelector('p');
  if (subtitleEl && bannerCfg.subtitle) {
    subtitleEl.textContent = bannerCfg.subtitle;
    (subtitleEl as HTMLElement).style.textShadow = '0 1.5px 6px rgba(0,0,0,0.9), 0 2px 10px rgba(0,0,0,0.7)';
  }
}

export function initSubpageBanners(): void {
  renderCurrentSubpageBanner();

  // Fetch live config from server API asynchronously
  fetch('/api/subpage-banners')
    .then(res => {
      if (res.ok) return res.json();
      throw new Error('Failed to fetch subpage banners');
    })
    .then((data: SubpageBannersMap) => {
      if (data && typeof data === 'object') {
        applySubpageBannersConfig(data);
      }
    })
    .catch(() => {
      // Fallback already active from subpage_banners.json
    });
}
