import homepageSectionsData from '../data/homepage_sections.json';
import { getCachedWeather, applyCachedWeatherToDOM } from '../services/weather';

export interface NavMenuItem {
  id?: string;
  label: string;
  url: string;
  target?: string;
  is_active?: boolean;
}

export function isCurrentDark(): boolean {
  return false;
}

export function applyThemeState(_isDark: boolean = false): void {
  if (typeof document !== 'undefined') {
    document.documentElement.classList.remove('dark');
    document.body.classList.remove('dark-mode', 'dark');
    document.documentElement.setAttribute('data-theme', 'light');
    if (typeof localStorage !== 'undefined') {
      localStorage.setItem('portal_theme', 'light');
    }
  }
}

export function togglePortalTheme(): void {
  applyThemeState(false);
}

// Attach to window and enforce light mode on initial script load
if (typeof window !== 'undefined') {
  (window as any).togglePortalTheme = togglePortalTheme;
  (window as any).applyThemeState = applyThemeState;
  (window as any).isCurrentDark = isCurrentDark;

  // Always force light mode
  applyThemeState(false);
}

let DYNAMIC_MENU_ITEMS: NavMenuItem[] = [
  { label: 'Trang chủ', url: '/index.html', target: '_self' },
  { label: 'Thủ tục hành chính', url: '/procedures.html', target: '_self' },
  { label: 'Bản đồ số Duy Hà', url: '/index.html#map-view', target: '_self' },
];

let SITE_LOGO = '/logo.jpg';
let SITE_TITLE_1 = 'CỔNG TRA CỨU THÔNG TIN';
let SITE_TITLE_2 = 'Phường Duy Hà — Tỉnh Ninh Bình';

// Initialize data from json
const headerSec = (homepageSectionsData as any[])?.find((s: any) => s.section_code === 'header_navbar');
if (headerSec) {
  if (headerSec.custom_title) SITE_TITLE_1 = headerSec.custom_title;
  if (headerSec.custom_subtitle) SITE_TITLE_2 = headerSec.custom_subtitle;
  if (headerSec.settings?.site_logo) SITE_LOGO = headerSec.settings.site_logo;
  if (headerSec.settings?.menu_items && Array.isArray(headerSec.settings.menu_items) && headerSec.settings.menu_items.length > 0) {
    DYNAMIC_MENU_ITEMS = headerSec.settings.menu_items.filter((m: any) => m.is_active !== false);
  }
}

/**
 * Determine which nav item is currently active based on URL pathname
 */
function getActivePage(): string {
  const path = window.location.pathname;
  if (path.includes('citizen-reception')) return 'citizen-reception';
  if (path.includes('feedback')) return 'feedback';
  if (path.includes('video-guides')) return 'video-guides';
  if (path.includes('policies')) return 'policies';
  if (path.includes('procedures')) return 'procedures';
  if (path.includes('agencies')) return 'agencies';
  if (path.includes('officials')) return 'officials';
  if (path.includes('tdp-merger')) return 'tdp-merger';
  if (path.includes('meritorious')) return 'meritorious';
  if (path.includes('waste-schedule')) return 'waste-schedule';
  return 'home';
}

const ACTIVE_BTN_CLASS = 'px-4 sm:px-5 py-2 rounded-full transition-all bg-[#1d7fe0] text-white font-bold shadow-md shadow-sky-500/20 whitespace-nowrap shrink-0 text-sm sm:text-base';
const INACTIVE_BTN_CLASS = 'px-4 sm:px-5 py-2 rounded-full transition-all text-slate-700 dark:text-slate-200 font-bold hover:text-[#1d7fe0] dark:hover:text-sky-400 hover:bg-sky-100/70 dark:hover:bg-slate-700/60 whitespace-nowrap shrink-0 text-sm sm:text-base';

// Active/Inactive nav item classes
function navItem(label: string, href: string, isActive: boolean, isOnclick?: string, target?: string): string {
  const cls = isActive ? ACTIVE_BTN_CLASS : INACTIVE_BTN_CLASS;
  const targetAttr = target && target === '_blank' ? ' target="_blank" rel="noopener noreferrer"' : '';
  if (isOnclick) {
    return `<button onclick="${isOnclick}" class="${cls}"><span class="whitespace-nowrap">${label}</span></button>`;
  }
  const preventReload = isActive && (!target || target === '_self') ? ` onclick="event.preventDefault();window.scrollTo({top:0,behavior:'smooth'});"` : '';
  return `<a href="${href}"${targetAttr}${preventReload} class="${cls}"><span class="whitespace-nowrap">${label}</span></a>`;
}

/**
 * Render the full header HTML
 */
export function renderHeader(): string {
  const active = getActivePage();
  const isHomePage = active === 'home';
  const weather = getCachedWeather();

  // Check if map view is currently open on homepage
  const mapContainer = typeof document !== 'undefined' ? document.getElementById('map-view-container') : null;
  const isMapOpen = isHomePage && ((mapContainer && !mapContainer.classList.contains('hidden')) || (typeof window !== 'undefined' && window.location.hash.includes('map')));

  // Mega menu items: on homepage use JS functions, on subpages use links
  const mapMegaOnclick = isHomePage ? "window.toggleMapView(); window.toggleMobileMenu()" : undefined;

  // Build dynamic nav capsule items
  const navCapsuleHtml = DYNAMIC_MENU_ITEMS.map(item => {
    const isItemHome = item.url === '/' || item.url === '/index.html';
    const isItemMap = item.url.includes('#map-view') || item.url.includes('#map');
    
    let isItemActive = false;
    if (isItemHome) {
      isItemActive = isHomePage && !isMapOpen;
    } else if (isItemMap) {
      isItemActive = isHomePage && isMapOpen;
    } else if (item.url.includes('procedures') && active === 'procedures') {
      isItemActive = true;
    } else if (item.url.includes('officials') && active === 'officials') {
      isItemActive = true;
    } else if (item.url.includes('waste-schedule') && active === 'waste-schedule') {
      isItemActive = true;
    }

    let onclickStr: string | undefined = undefined;
    if (isHomePage) {
      if (isItemHome) onclickStr = "window.showPortalTab('home');";
      else if (isItemMap) onclickStr = "window.toggleMapView();";
    }

    return navItem(item.label, item.url, isItemActive, onclickStr, item.target);
  }).join('\n');

  return `
    <div class="max-w-7xl mx-auto px-3 sm:px-6 py-2.5 sm:py-3.5 flex items-center justify-between gap-2 sm:gap-4">
      <!-- Brand Logo -->
      <a href="/index.html"${isHomePage ? ` onclick="event.preventDefault(); window.showPortalTab('home');"` : ''}
        class="flex items-center gap-2 sm:gap-3 cursor-pointer select-none shrink-0 min-w-0 max-w-[calc(100vw-75px)] sm:max-w-none" style="text-decoration:none;">
        <div class="w-10 h-10 sm:w-12 sm:h-12 md:w-13 md:h-13 shrink-0 flex items-center justify-center">
          <img src="${SITE_LOGO}" id="header-site-logo" alt="Logo Phường Duy Hà" class="w-full h-full object-contain filter drop-shadow-sm" onerror="this.onerror=null; this.src='/logo.jpg'" />
        </div>
        <div class="min-w-0 overflow-hidden">
          <b id="header-site-title-1"
            class="text-[#1565c0] dark:text-sky-400 text-sm sm:text-lg md:text-xl lg:text-[1.3rem] font-black tracking-tight leading-tight block truncate whitespace-nowrap">${SITE_TITLE_1}</b>
          <small id="header-site-title-2"
            style="font-family: 'Roboto', sans-serif; letter-spacing: 0.05em;"
            class="text-slate-800 dark:text-slate-200 text-[10px] sm:text-xs md:text-[13px] font-normal uppercase tracking-wider block mt-0.5 truncate whitespace-nowrap">${SITE_TITLE_2}</small>
        </div>
      </a>

      <!-- Primary Navigation Capsule (Visible on Desktop >= 1024px) -->
      <nav class="hidden lg:flex items-center gap-1.5 sm:gap-2 bg-sky-50/90 dark:bg-slate-800/90 p-1.5 sm:p-2 rounded-full border border-sky-200/70 dark:border-slate-700/70 text-sm sm:text-base font-bold shadow-sm whitespace-nowrap shrink-0">
        ${navCapsuleHtml}
        <div class="h-5 w-px bg-slate-300 dark:bg-slate-700 mx-1 shrink-0"></div>
        <button onclick="window.toggleMobileMenu()"
          class="p-2 sm:p-2.5 rounded-full text-slate-700 dark:text-slate-200 font-bold hover:text-[#1d7fe0] dark:hover:text-sky-400 hover:bg-sky-100/70 dark:hover:bg-slate-700/60 transition-colors flex items-center justify-center cursor-pointer shrink-0"
          title="Tất cả danh mục menu">
          <span class="material-symbols-outlined text-xl sm:text-2xl">menu</span>
        </button>
      </nav>

      <!-- Right Header Actions -->
      <div class="flex items-center gap-2 sm:gap-2.5 shrink-0">
        <!-- Weather Widget Mini (Visible on Desktop >= 1024px) -->
        <div id="weather-widget-portal"
          class="hidden lg:flex items-center gap-1.5 sm:gap-2 bg-sky-50 dark:bg-slate-800 text-sky-950 dark:text-sky-200 px-3 sm:px-4 py-2 sm:py-2.5 rounded-full border border-sky-200/60 dark:border-slate-700 text-xs sm:text-sm md:text-base font-bold">
          <span class="material-symbols-outlined text-amber-500 text-base sm:text-lg" id="weather-icon-portal">${weather.icon}</span>
          <span id="weather-temp-portal">${weather.temp}</span>
          <span class="text-slate-300">|</span>
          <span id="weather-desc-portal">${weather.desc}</span>
        </div>

        <!-- Mobile & Tablet Menu Toggle Button (< 1024px screens) -->
        <button id="mobile-menu-toggle-btn" onclick="window.toggleMobileMenu()"
          class="lg:hidden flex items-center justify-center w-10 h-10 sm:w-11 sm:h-11 bg-[#3399fe] text-white rounded-full shadow-md shadow-sky-500/25 active:scale-95 transition-all cursor-pointer shrink-0"
          title="Tất cả danh mục dịch vụ">
          <span class="material-symbols-outlined text-xl sm:text-2xl">menu</span>
        </button>
      </div>
    </div>

    <!-- Mega Navigation Overlay Menu (Positioned directly below header like vanban.hanoi.gov.vn, no backdrop blur) -->
    <div id="mobile-nav-menu"
      class="hidden fixed inset-0 z-[4000] flex items-start justify-center pt-16 sm:pt-20 px-3 sm:px-6 pb-6 overflow-y-auto bg-black/60 transition-all duration-200"
      onclick="window.toggleMobileMenu()">
      
      <!-- Mega Menu Container attached right below header bar -->
      <div class="relative max-w-6xl w-full bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-3xl p-6 sm:p-10 shadow-2xl space-y-7 max-h-[calc(100vh-5.5rem)] overflow-y-auto transition-all"
        onclick="event.stopPropagation()">
        
        <!-- Header title + Close button 'X' -->
        <div class="flex items-center justify-between pb-5 border-b border-slate-200 dark:border-slate-800">
          <div class="flex items-center gap-3 text-[#1d7fe0] dark:text-sky-400 font-black text-lg sm:text-2xl">
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-sky-100 dark:bg-sky-950 flex items-center justify-center text-[#1d7fe0] shrink-0 shadow-sm">
              <span class="material-symbols-outlined text-2xl sm:text-3xl">grid_view</span>
            </div>
            <span>SƠ ĐỒ DANH MỤC &amp; DỊCH VỤ CÔNG PHƯỜNG DUY HÀ</span>
          </div>
          <button onclick="window.toggleMobileMenu()"
            class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-slate-100 hover:bg-red-500 hover:text-white dark:bg-slate-800 dark:hover:bg-red-600 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-all shadow-sm active:scale-95 cursor-pointer shrink-0"
            title="Đóng sơ đồ danh mục">
            <span class="material-symbols-outlined text-2xl sm:text-3xl">close</span>
          </button>
        </div>

        <!-- 4-Column Grid layout like Hanoi Vanban sitemap (Clean, balanced 3 items per column) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-7 sm:gap-8">

          <!-- Column 1: Dịch vụ công & Thủ tục -->
          <div class="space-y-3.5">
            <h4 class="text-sm sm:text-base md:text-[1.05rem] font-black text-[#1d7fe0] dark:text-sky-300 uppercase tracking-wider flex items-center gap-2.5 border-b-2 border-sky-500/30 pb-3 mb-3.5">
              <span class="material-symbols-outlined text-[#1d7fe0] text-xl sm:text-2xl">assignment</span>
              <span>Dịch vụ công &amp; Thủ tục</span>
            </h4>
            <ul class="space-y-3 text-sm sm:text-base md:text-[1.05rem] font-bold text-slate-800 dark:text-slate-100">
              <li>
                <a href="/procedures.html?tab=procedures" class="hover:text-[#1d7fe0] dark:hover:text-sky-400 flex items-center gap-2.5 py-1.5 transition-colors ${active === 'procedures' ? 'text-[#1d7fe0] font-black' : ''}">
                  <span class="w-2 h-2 rounded-sm bg-[#1d7fe0] shrink-0"></span>
                  <span>Thủ tục hành chính</span>
                </a>
              </li>
              <li>
                <a href="/procedures.html?tab=policies" class="hover:text-[#1d7fe0] dark:hover:text-sky-400 flex items-center gap-2.5 py-1.5 transition-colors">
                  <span class="w-2 h-2 rounded-sm bg-[#1d7fe0] shrink-0"></span>
                  <span>Chính sách &amp; Quy định</span>
                </a>
              </li>
              <li>
                <a href="/procedures.html?tab=videos" class="hover:text-[#1d7fe0] dark:hover:text-sky-400 flex items-center gap-2.5 py-1.5 transition-colors">
                  <span class="w-2 h-2 rounded-sm bg-[#1d7fe0] shrink-0"></span>
                  <span>Video hướng dẫn thủ tục</span>
                </a>
              </li>
            </ul>
          </div>

          <!-- Column 2: Lịch công tác & Tiếp dân -->
          <div class="space-y-3.5">
            <h4 class="text-sm sm:text-base md:text-[1.05rem] font-black text-[#1d7fe0] dark:text-sky-300 uppercase tracking-wider flex items-center gap-2.5 border-b-2 border-sky-500/30 pb-3 mb-3.5">
              <span class="material-symbols-outlined text-[#1d7fe0] text-xl sm:text-2xl">calendar_month</span>
              <span>Lịch công tác &amp; Tiếp dân</span>
            </h4>
            <ul class="space-y-3 text-sm sm:text-base md:text-[1.05rem] font-bold text-slate-800 dark:text-slate-100">
              <li>
                <a href="/citizen-reception.html" class="hover:text-[#1d7fe0] dark:hover:text-sky-400 flex items-center gap-2.5 py-1.5 transition-colors ${active === 'citizen-reception' ? 'text-[#1d7fe0] font-black' : ''}">
                  <span class="w-2 h-2 rounded-sm bg-[#1d7fe0] shrink-0"></span>
                  <span>Lịch tiếp công dân định kỳ</span>
                </a>
              </li>
              <li>
                <a href="/waste-schedule.html" class="hover:text-[#1d7fe0] dark:hover:text-sky-400 flex items-center gap-2.5 py-1.5 transition-colors ${active === 'waste-schedule' ? 'text-[#1d7fe0] font-black' : ''}">
                  <span class="w-2 h-2 rounded-sm bg-[#1d7fe0] shrink-0"></span>
                  <span>Lịch thu gom rác sinh hoạt</span>
                </a>
              </li>
            </ul>
          </div>

          <!-- Column 3: Tổ chức & Bộ máy chính quyền -->
          <div class="space-y-3.5">
            <h4 class="text-sm sm:text-base md:text-[1.05rem] font-black text-[#1d7fe0] dark:text-sky-300 uppercase tracking-wider flex items-center gap-2.5 border-b-2 border-sky-500/30 pb-3 mb-3.5">
              <span class="material-symbols-outlined text-[#1d7fe0] text-xl sm:text-2xl">account_balance</span>
              <span>Tổ chức &amp; Chính quyền</span>
            </h4>
            <ul class="space-y-3 text-sm sm:text-base md:text-[1.05rem] font-bold text-slate-800 dark:text-slate-100">
              <li>
                <a href="/agencies.html" class="hover:text-[#1d7fe0] dark:hover:text-sky-400 flex items-center gap-2.5 py-1.5 transition-colors ${active === 'agencies' ? 'text-[#1d7fe0] font-black' : ''}">
                  <span class="w-2 h-2 rounded-sm bg-[#1d7fe0] shrink-0"></span>
                  <span>Danh sách Cơ quan hành chính</span>
                </a>
              </li>
              <li>
                <a href="/officials.html" class="hover:text-[#1d7fe0] dark:hover:text-sky-400 flex items-center gap-2.5 py-1.5 transition-colors ${active === 'officials' ? 'text-[#1d7fe0] font-black' : ''}">
                  <span class="w-2 h-2 rounded-sm bg-[#1d7fe0] shrink-0"></span>
                  <span>Danh sách Cán bộ Phường</span>
                </a>
              </li>
              <li>
                <a href="/tdp-merger.html" class="hover:text-[#1d7fe0] dark:hover:text-sky-400 flex items-center gap-2.5 py-1.5 transition-colors ${active === 'tdp-merger' ? 'text-[#1d7fe0] font-black' : ''}">
                  <span class="w-2 h-2 rounded-sm bg-[#1d7fe0] shrink-0"></span>
                  <span>Tổ dân phố &amp; Cán bộ TDP</span>
                </a>
              </li>
            </ul>
          </div>

          <!-- Column 4: An sinh xã hội & Tra cứu -->
          <div class="space-y-3.5">
            <h4 class="text-sm sm:text-base md:text-[1.05rem] font-black text-[#1d7fe0] dark:text-sky-300 uppercase tracking-wider flex items-center gap-2.5 border-b-2 border-sky-500/30 pb-3 mb-3.5">
              <span class="material-symbols-outlined text-[#1d7fe0] text-xl sm:text-2xl">explore</span>
              <span>An sinh xã hội &amp; Tra cứu</span>
            </h4>
            <ul class="space-y-3 text-sm sm:text-base md:text-[1.05rem] font-bold text-slate-800 dark:text-slate-100">
              <li>
                <a href="/meritorious-families.html" class="hover:text-[#1d7fe0] dark:hover:text-sky-400 flex items-center gap-2.5 py-1.5 transition-colors ${active === 'meritorious' ? 'text-[#1d7fe0] font-black' : ''}">
                  <span class="w-2 h-2 rounded-sm bg-[#1d7fe0] shrink-0"></span>
                  <span>Gia đình chính sách &amp; Người có công</span>
                </a>
              </li>
              <li>
                <a href="/feedback.html" class="hover:text-[#1d7fe0] dark:hover:text-sky-400 flex items-center gap-2.5 py-1.5 transition-colors ${active === 'feedback' ? 'text-[#1d7fe0] font-black' : ''}">
                  <span class="w-2 h-2 rounded-sm bg-[#1d7fe0] shrink-0"></span>
                  <span>Gửi Phản ánh &amp; Kiến nghị</span>
                </a>
              </li>
              <li>
                <a href="/index.html#map-view" ${mapMegaOnclick ? `onclick="${mapMegaOnclick}"` : ''} class="hover:text-[#1d7fe0] dark:hover:text-sky-400 flex items-center gap-2.5 py-1.5 transition-colors">
                  <span class="w-2 h-2 rounded-sm bg-[#1d7fe0] shrink-0"></span>
                  <span>Tra cứu trên Bản đồ số Duy Hà</span>
                </a>
              </li>
            </ul>
          </div>

        </div>
      </div>
    </div>`;
}

export function applySharedHeaderConfig(section: any): void {
  if (!section) return;

  if (section.custom_title) SITE_TITLE_1 = section.custom_title;
  if (section.custom_subtitle) SITE_TITLE_2 = section.custom_subtitle;
  if (section.settings?.site_logo) SITE_LOGO = section.settings.site_logo;
  if (section.settings?.menu_items && Array.isArray(section.settings.menu_items)) {
    const activeItems = section.settings.menu_items.filter((m: any) => m.is_active !== false);
    if (activeItems.length > 0) {
      DYNAMIC_MENU_ITEMS = activeItems;
    }
  }

  const headerEl = document.getElementById('app-header');
  if (headerEl) {
    headerEl.innerHTML = renderHeader();
    applyCachedWeatherToDOM();

    const menuEl = document.getElementById('mobile-nav-menu');
    if (menuEl) {
      document.body.appendChild(menuEl);
    }
  }
}

(window as any).toggleMobileMenu = function () {
  const m = document.getElementById('mobile-nav-menu');
  if (m) {
    if (m.classList.contains('hidden')) {
      m.classList.remove('hidden');
    } else {
      m.classList.add('hidden');
    }
  }
};

if (typeof window !== 'undefined') {
  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      const m = document.getElementById('mobile-nav-menu');
      if (m && !m.classList.contains('hidden')) {
        m.classList.add('hidden');
      }
    }
  });
}

/**
 * Initialize header by injecting into #app-header element
 */
export function initSharedHeader(): void {
  const headerEl = document.getElementById('app-header');
  if (headerEl) {
    headerEl.className = 'sticky top-0 z-[3000] bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border-b border-sky-100 dark:border-slate-800 transition-colors shadow-xs';
    headerEl.innerHTML = renderHeader();

    const menuEl = document.getElementById('mobile-nav-menu');
    if (menuEl) {
      document.body.appendChild(menuEl);
    }
  }
}

if (typeof window !== 'undefined') {
  (window as any).initSharedHeader = initSharedHeader;
}
