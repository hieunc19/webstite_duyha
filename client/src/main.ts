import './style.css';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import 'leaflet.markercluster';
import 'leaflet.markercluster/dist/MarkerCluster.css';
import 'leaflet.markercluster/dist/MarkerCluster.Default.css';

import type { Place, Official, Neighborhood, Department } from './types';
import placesData from './data/places.json';
import officialsData from './data/officials.json';
import departmentsData from './data/departments.json';
import neighborhoodsData from './data/neighborhoods.json';
const NEIGHBORHOODS: Neighborhood[] = neighborhoodsData as Neighborhood[];
import { DUY_HA_BOUNDARY } from './data/duyHaBoundary';
import { initWeatherWidget } from './services/weather';
import meritoriousFamiliesData from './data/meritorious_families.json';
import settingsData from './data/settings.json';
import homepageSectionsData from './data/homepage_sections.json';
import tdpOfficialsData from './data/tdp_officials.json';
const ALL_TDP_OFFICIALS = tdpOfficialsData as any[];
import proceduresData from './data/procedures.json';
import procedureVideosData from './data/procedure_videos.json';
import policiesData from './data/policies.json';
import { CSKV_MAP } from './data/tdpOfficials';
import { applySharedHeaderConfig, initSharedHeader, applyThemeState } from './components/sharedHeader';
import { applySharedFooterConfig, initSharedFooter } from './components/sharedFooter';
import { initSubpageBanners } from './components/sharedSubpageBanner';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import * as XLSX from 'xlsx';

declare global {
  interface Window {
    Swal: typeof Swal;
    showPortalTab: (tabName: 'home' | 'neighborhoods' | 'merger' | 'officials' | 'meritorious') => void;
    toggleMapView: () => void;
    viewPlaceDetail: (id: number) => void;
    closePortalModal: () => void;
    openTdpModal: () => void;
    closeTdpModal: () => void;
    openAllOfficialsModal: () => void;
    openAllOfficialsModalForTdp: (tdpName: string) => void;
    closeAllOfficialsModal: () => void;
    filterAllOfficialsTable: (keyword: string) => void;
    openAllAgenciesModal: () => void;
    closeAllAgenciesModal: () => void;
    filterAllAgenciesTable: () => void;
    showMeritoriousDetail: (id: number) => void;
    closeMeritoriousModal: () => void;
    filterMeritoriousByEvent: (eventId: number | 'all') => void;
    filterPortalCategory: (category: string) => void;
    filterOfficialsByNeighborhood: (neighborhood: string) => void;
    renderOfficialsGrid: () => void;
    toggleMobileMenu: () => void;
    toggleMapPlacesDock: () => void;
    scrollMapPlacesDock: (direction: 'prev' | 'next' | 'left' | 'right') => void;
    focusMapPlace: (id: number) => void;
    toggleMobileStatsPanel: () => void;
    switchTdpMobileTab: (tab: 'old' | 'new', prefix?: string) => void;
    pannellum: any;
  }
}

function formatStorageUrl(path: string | null | undefined): string {
  if (!path) return '/hero-bg.jpg';
  if (path.startsWith('http://') || path.startsWith('https://')) return path;
  if (path.startsWith('/storage/')) return path;
  if (path.startsWith('/')) return path;
  return `/storage/${path.replace(/^\/+/, '')}`;
}

function getLocalFileUrl(urlOrPath: string | null | undefined): string {
  if (!urlOrPath) return '';
  if (urlOrPath.includes('/storage/')) {
    const idx = urlOrPath.indexOf('/storage/');
    return urlOrPath.substring(idx);
  }
  if (urlOrPath.startsWith('http://') || urlOrPath.startsWith('https://')) {
    return urlOrPath;
  }
  return formatStorageUrl(urlOrPath);
}

// Chuẩn hóa dữ liệu ban đầu từ database JSON xuất xưởng (0ms delay, không có dữ liệu mock)
const INITIAL_PLACES: Place[] = (placesData as any[]).map(p => ({
  ...p,
  image: formatStorageUrl(p.image)
}));

const INITIAL_OFFICIALS: Official[] = (officialsData as any[]).map(o => ({
  ...o,
  avatar: formatStorageUrl(o.avatar)
}));

const INITIAL_DEPARTMENTS: Department[] = (departmentsData as Department[]).sort((a, b) => (a.sort_order || 0) - (b.sort_order || 0));

class PortalApp {
  private places: Place[] = INITIAL_PLACES;
  private officials: Official[] = INITIAL_OFFICIALS;
  private departments: Department[] = INITIAL_DEPARTMENTS;
  private neighborhoods: Neighborhood[] = NEIGHBORHOODS;
  private meritoriousFamilies: any[] = meritoriousFamiliesData;
  private proceduresList: any[] = proceduresData as any[];
  private procedureVideos: any[] = procedureVideosData as any[];
  private policiesList: any[] = policiesData as any[];
  private activeCategory: string = 'all';
  private currentPlace: Place | null = null;
  private procedureActiveTab = 'popular';
  private selectedAgencyIds: number[] = [];

  // Meritorious Live Document & Spreadsheet Viewer State
  private selectedMeritoriousId: number | null = null;
  private meritoriousSheetIndex: number = 0;
  private meritoriousPage: number = 1;
  private meritoriousRowsPerPage: number = 50;
  private meritoriousWorkbookCache: Map<string, any> = new Map();
  private meritoriousParsedWorkbook: any = null;

  // Leaflet Map state
  private map: L.Map | null = null;

  constructor() {
    this.initTheme();

    // 0ms Immediate Synchronous Render from pre-bundled data (Eliminates 1s mock flash and layout shift)
    if (settingsData && (settingsData as any).cards) {
      this.renderStatCardsList((settingsData as any).cards);
    }
    if (homepageSectionsData && Array.isArray(homepageSectionsData)) {
      this.applyHomepageLayout(homepageSectionsData);
    }

    this.renderPortalGrid();
    this.renderOfficialsGrid();
    this.populateOfficialNeighborhoodSelect();
    this.renderTdpModalTables();
    this.renderMeritoriousSection();
    this.renderProceduresSection();

    this.initSearch();
    this.initEventListeners();

    // FOUC Prevention: Reveal content now that all synchronous renders are complete
    document.body.classList.add('js-hydrated');

    // Background asynchronous re-fetch to sync any real-time DB changes
    this.initPortalData();
    this.fetchProceduresData();

    // Wire global window methods
    window.Swal = Swal;
    window.showPortalTab = this.showPortalTab.bind(this);
    window.toggleMapView = this.toggleMapView.bind(this);
    window.viewPlaceDetail = this.viewPlaceDetail.bind(this);
    window.closePortalModal = this.closePortalModal.bind(this);
    window.openTdpModal = this.openTdpModal.bind(this);
    window.closeTdpModal = this.closeTdpModal.bind(this);
    window.openAllOfficialsModal = this.openAllOfficialsModal.bind(this);
    window.openAllOfficialsModalForTdp = this.openAllOfficialsModalForTdp.bind(this);
    window.closeAllOfficialsModal = this.closeAllOfficialsModal.bind(this);
    window.filterAllOfficialsTable = this.renderAllOfficialsTable.bind(this);
    window.openAllAgenciesModal = this.openAllAgenciesModal.bind(this);
    window.closeAllAgenciesModal = this.closeAllAgenciesModal.bind(this);
    window.showMeritoriousDetail = this.showMeritoriousDetail.bind(this);
    window.closeMeritoriousModal = () => {};
    window.filterMeritoriousByEvent = (_eventId: number | 'all') => {
      this.renderMeritoriousSection();
    };
    window.filterPortalCategory = this.filterPortalCategory.bind(this);
    window.filterOfficialsByNeighborhood = this.filterOfficialsByNeighborhood.bind(this);
    window.renderOfficialsGrid = () => this.renderOfficialsGrid();
    window.addEventListener('spa:navigated', () => {
      this.renderPortalGrid();
      this.renderOfficialsGrid();
      this.populateOfficialNeighborhoodSelect();
      this.renderTdpModalTables();
      this.renderMeritoriousSection();
      this.renderProceduresSection();
      this.initSearch();
      this.initEventListeners();
      triggerStatCardsCountUp();
    });
    window.toggleMobileMenu = () => {
      const menu = document.getElementById('mobile-nav-menu');
      if (menu) menu.classList.toggle('hidden');
    };
    window.toggleMapPlacesDock = () => {
      const el = document.getElementById('map-places-dock-wrapper') || document.getElementById('map-places-carousel');
      const icon = document.getElementById('map-places-toggle-icon');
      if (el) {
        el.classList.toggle('hidden');
        if (icon) {
          icon.style.transform = el.classList.contains('hidden') ? 'rotate(180deg)' : 'rotate(0deg)';
        }
      }
    };
    window.scrollMapPlacesDock = (direction: 'prev' | 'next' | 'left' | 'right') => {
      const container = document.getElementById('map-places-carousel');
      if (container) {
        const scrollAmount = direction === 'prev' || direction === 'left' ? -320 : 320;
        container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
      }
    };
    window.focusMapPlace = (id: number) => {
      const p = this.places.find(item => item.id === id);
      if (p && this.map) {
        this.map.flyTo([p.lat, p.lng], 16.5, { animate: true, duration: 0.8 });
      }
    };
    window.toggleMobileStatsPanel = () => {
      const panel = document.getElementById('stats-panel');
      if (panel) {
        if (panel.classList.contains('hidden')) {
          panel.classList.remove('hidden');
          panel.classList.add('flex');
        } else {
          panel.classList.add('hidden');
          panel.classList.remove('flex');
        }
      }
    };
    window.switchTdpMobileTab = (tab: 'old' | 'new', prefix: string = 'page') => {
      const container = document.getElementById(`${prefix}-tdp-tables-container`);
      const slideNew = document.getElementById(`${prefix}-tdp-slide-new`);
      const btnOld = document.getElementById(`${prefix}-tdp-tab-old`);
      const btnNew = document.getElementById(`${prefix}-tdp-tab-new`);

      if (!container) return;

      if (tab === 'old') {
        container.scrollTo({ left: 0, behavior: 'smooth' });
        if (btnOld) btnOld.className = 'flex-1 py-2 px-3 rounded-xl text-xs font-black transition-all bg-white dark:bg-slate-900 text-blue-700 dark:text-blue-400 shadow-sm flex items-center justify-center gap-1.5';
        if (btnNew) btnNew.className = 'flex-1 py-2 px-3 rounded-xl text-xs font-black transition-all text-slate-600 dark:text-slate-400 hover:text-emerald-600 flex items-center justify-center gap-1.5';
      } else {
        const targetLeft = slideNew ? slideNew.offsetLeft - container.offsetLeft : container.scrollWidth / 2;
        container.scrollTo({ left: targetLeft, behavior: 'smooth' });
        if (btnNew) btnNew.className = 'flex-1 py-2 px-3 rounded-xl text-xs font-black transition-all bg-white dark:bg-slate-900 text-emerald-700 dark:text-emerald-400 shadow-sm flex items-center justify-center gap-1.5';
        if (btnOld) btnOld.className = 'flex-1 py-2 px-3 rounded-xl text-xs font-black transition-all text-slate-600 dark:text-slate-400 hover:text-blue-600 flex items-center justify-center gap-1.5';
      }
    };

    // Attach scroll sync for mobile TDP swipe containers
    setTimeout(() => {
      ['page', 'modal'].forEach(prefix => {
        const container = document.getElementById(`${prefix}-tdp-tables-container`);
        if (container) {
          container.addEventListener('scroll', () => {
            const scrollLeft = container.scrollLeft;
            const width = container.clientWidth;
            const btnOld = document.getElementById(`${prefix}-tdp-tab-old`);
            const btnNew = document.getElementById(`${prefix}-tdp-tab-new`);
            if (scrollLeft > width / 3) {
              if (btnNew) btnNew.className = 'flex-1 py-2 px-3 rounded-xl text-xs font-black transition-all bg-white dark:bg-slate-900 text-emerald-700 dark:text-emerald-400 shadow-sm flex items-center justify-center gap-1.5';
              if (btnOld) btnOld.className = 'flex-1 py-2 px-3 rounded-xl text-xs font-black transition-all text-slate-600 dark:text-slate-400 hover:text-blue-600 flex items-center justify-center gap-1.5';
            } else {
              if (btnOld) btnOld.className = 'flex-1 py-2 px-3 rounded-xl text-xs font-black transition-all bg-white dark:bg-slate-900 text-blue-700 dark:text-blue-400 shadow-sm flex items-center justify-center gap-1.5';
              if (btnNew) btnNew.className = 'flex-1 py-2 px-3 rounded-xl text-xs font-black transition-all text-slate-600 dark:text-slate-400 hover:text-emerald-600 flex items-center justify-center gap-1.5';
            }
          });
        }
      });
    }, 500);

    // Weather widget
    initWeatherWidget();

    // Render initial stat cards from seed JSON immediately (0ms delay)
    if (settingsData && Array.isArray((settingsData as any).cards)) {
      this.renderStatCardsList((settingsData as any).cards);
    }

    // Apply initial homepage sections layout & ordering immediately (0ms delay)
    if (Array.isArray(homepageSectionsData)) {
      this.applyHomepageLayout(homepageSectionsData);
    }

    // Auto open map view if navigating with #map-view hash
    this.checkInitialRoute();
    window.addEventListener('hashchange', () => this.checkInitialRoute());
  }

  private checkInitialRoute() {
    const hash = window.location.hash;
    const search = window.location.search;
    const preloadStyle = document.getElementById('map-preload-style');
    if (hash === '#map-view' || hash === '#map' || search.includes('view=map')) {
      const mapContainer = document.getElementById('map-view-container');
      const mainView = document.getElementById('portal-main-view');
      if (mainView) mainView.classList.add('hidden');
      if (mapContainer) {
        mapContainer.classList.remove('hidden');
        mapContainer.classList.add('flex');
        if (!this.map) {
          this.initLeafletMap();
        } else {
          setTimeout(() => this.map?.invalidateSize(), 100);
        }
      }
      if (preloadStyle) preloadStyle.remove();
    } else {
      if (preloadStyle) preloadStyle.remove();
    }
  }

  public getCurrentPlace(): Place | null {
    return this.currentPlace;
  }

  private initTheme() {
    applyThemeState(false);
  }

  private async initPortalData() {
    try {
      const [placesRes, officialsRes, departmentsRes, neighborhoodsRes, familiesRes, tdpOfficialsRes, settingsRes, sectionsRes] = await Promise.all([
        fetch('/api/places').catch(() => null),
        fetch('/api/officials').catch(() => null),
        fetch('/api/departments').catch(() => null),
        fetch('/api/neighborhoods').catch(() => null),
        fetch('/api/meritorious-families').catch(() => null),
        fetch('/api/tdp-officials').catch(() => null),
        fetch('/api/settings').catch(() => null),
        fetch('/api/homepage-sections').catch(() => null)
      ]);

      if (placesRes && placesRes.ok) {
        const data = await placesRes.json();
        if (Array.isArray(data) && data.length > 0) {
          this.places = data.map(p => ({
            ...p,
            image: formatStorageUrl(p.image)
          }));
          this.renderPortalGrid();
          this.renderMapPlacesCarousel();
        }
      }

      if (departmentsRes && departmentsRes.ok) {
        const dData = await departmentsRes.json();
        if (Array.isArray(dData)) {
          this.departments = dData.sort((a: any, b: any) => (a.sort_order || 0) - (b.sort_order || 0));
        }
      }

      if (officialsRes && officialsRes.ok) {
        const officialsData = await officialsRes.json();
        if (Array.isArray(officialsData)) {
          this.officials = officialsData.map(o => ({
            ...o,
            avatar: formatStorageUrl(o.avatar)
          }));
        }
      }

      this.renderOfficialsGrid();
      this.populateOfficialNeighborhoodSelect();

      if (neighborhoodsRes && neighborhoodsRes.ok) {
        const nData = await neighborhoodsRes.json();
        if (Array.isArray(nData) && nData.length > 0) {
          this.neighborhoods = nData.map((item: any) => ({
            id: item.id,
            name: item.name,
            type: item.type,
            group_code: item.group_code,
            households: Number(item.households) || 0,
            people: Number(item.people) || 0,
            area_ha: Number(item.area_ha) || 0,
            leader_name: item.leader_name || null,
            leader_phone: item.leader_phone || null,
            status: item.status || 'active'
          }));
          this.renderTdpModalTables();
        }
      }

      if (familiesRes && familiesRes.ok) {
        const familiesData = await familiesRes.json();
        if (Array.isArray(familiesData)) {
          this.meritoriousFamilies = familiesData;
          this.renderMeritoriousSection();
        }
      }

      if (tdpOfficialsRes && tdpOfficialsRes.ok) {
        const tdpData = await tdpOfficialsRes.json();
        if (Array.isArray(tdpData) && tdpData.length > 0) {
          ALL_TDP_OFFICIALS.length = 0;
          ALL_TDP_OFFICIALS.push(...tdpData.map((item: any) => ({
            tt: item.tt ?? item.id ?? 1,
            tdp: item.tdp ?? item.tdp_name ?? '',
            biThuName: item.biThuName ?? item.bi_thu_name ?? '',
            biThuPhone: item.biThuPhone ?? item.bi_thu_phone ?? '',
            toTruongName: item.toTruongName ?? item.to_truong_name ?? '',
            toTruongPhone: item.toTruongPhone ?? item.to_truong_phone ?? '',
            cskvName: item.cskvName ?? item.cskv_name ?? '',
            cskvPhone: item.cskvPhone ?? item.cskv_phone ?? '',
            matTanName: item.matTanName ?? item.mat_tan_name ?? '',
            matTanPhone: item.matTanPhone ?? item.mat_tan_phone ?? '',
            nguoiCaoTuoi: item.nguoiCaoTuoi ?? item.nguoi_cao_tuoi ?? '',
            phuNu: item.phuNu ?? item.phu_nu ?? '',
            nongDan: item.nongDan ?? item.nong_dan ?? '',
            ccb: item.ccb ?? '',
            doanThanhNien: item.doanThanhNien ?? item.doan_thanh_nien ?? ''
          })));
        }
      }

      if (settingsRes && settingsRes.ok) {
        const s = await settingsRes.json();
        if (s && Array.isArray(s.cards) && s.cards.length > 0) {
          this.renderStatCardsList(s.cards);
        }
      }

      if (sectionsRes && sectionsRes.ok) {
        const sectionsData = await sectionsRes.json();
        if (Array.isArray(sectionsData) && sectionsData.length > 0) {
          this.applyHomepageLayout(sectionsData);
        }
      }
    } catch (e) {
      console.log('API call fallback to seed data');
    }
  }

  private applyHomepageLayout(sections: any[]) {
    if (!Array.isArray(sections) || sections.length === 0) return;
    const parentContainer = document.getElementById('portal-main-view');
    if (!parentContainer) return;

    const codeToIdMap: Record<string, string> = {
      'hero_banner': 'section-hero-banner',
      'stats_cards': 'section-stats-cards',
      'agencies_grid': 'section-agencies-grid',
      'procedures_utilities': 'section-hdsd-procedure',
      'hdsd_procedure': 'section-hdsd-procedure'
    };

    const titleIdMap: Record<string, string> = {
      'agencies_grid': 'title-agencies-grid',
      'hdsd_procedure': 'title-procedures_utilities',
      'procedures_utilities': 'title-procedures_utilities',
    };

    const subtitleIdMap: Record<string, string> = {
      'agencies_grid': 'subtitle-agencies-grid',
      'hdsd_procedure': 'subtitle-procedures_utilities',
      'procedures_utilities': 'subtitle-procedures_utilities',
    };


    const sorted = [...sections].sort((a, b) => (a.sort_order || 0) - (b.sort_order || 0));

    // Track which section IDs were processed by the API sort order
    const processedIds = new Set<string>();

    sorted.forEach((sec: any) => {
      if (sec.section_code === 'header_navbar') {
        applySharedHeaderConfig(sec);
        return;
      }

      if (sec.section_code === 'footer_section') {
        applySharedFooterConfig(sec);
        return;
      }

      // Handle custom section blocks
      if (sec.section_code && sec.section_code.startsWith('custom_')) {
        let customEl = document.getElementById(`section-${sec.section_code}`);
        if (!customEl) {
          customEl = document.createElement('section');
          customEl.id = `section-${sec.section_code}`;
          customEl.className = 'max-w-7xl mx-auto px-4 sm:px-6 mt-12';
        }

        const badgeHtml = sec.settings?.badge ? `<span class="bg-amber-400 text-slate-900 text-xs font-black px-2.5 py-1 rounded-lg uppercase tracking-wider">${sec.settings.badge}</span>` : '';
        const btnHtml = sec.settings?.btn_text ? `<a href="${sec.settings?.btn_url || '#'}" class="px-5 py-2.5 rounded-xl bg-white text-[#1d7fe0] font-black text-sm shadow-md hover:bg-sky-50 transition-all shrink-0">${sec.settings.btn_text} ↗</a>` : '';

        customEl.innerHTML = `
          <div class="bg-gradient-to-r from-[#1d7fe0] via-[#1668c2] to-[#124285] text-white p-6 sm:p-8 rounded-3xl shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="space-y-2 flex-1">
              ${badgeHtml}
              <h2 class="text-xl sm:text-2xl font-black text-white leading-snug">${sec.custom_title || sec.name}</h2>
              ${sec.custom_subtitle ? `<p class="text-sky-100 text-sm font-medium leading-relaxed">${sec.custom_subtitle}</p>` : ''}
              ${sec.settings?.content ? `<div class="text-sky-50 text-xs sm:text-sm mt-3 pt-3 border-t border-white/20 whitespace-pre-line">${sec.settings.content}</div>` : ''}
            </div>
            ${btnHtml}
          </div>
        `;

        if (sec.is_visible === false) {
          customEl.classList.add('hidden');
        } else {
          customEl.classList.remove('hidden');
          parentContainer.appendChild(customEl);
        }
        return;
      }

      const elementId = codeToIdMap[sec.section_code];
      if (!elementId) return;

      const el = document.getElementById(elementId);
      if (!el) return;

      processedIds.add(elementId);

      if (sec.is_visible === false) {
        el.classList.add('hidden');
        el.style.display = 'none';
      } else {
        el.classList.remove('hidden');
        el.style.display = '';
        parentContainer.appendChild(el);
      }

      if (sec.section_code === 'hero_banner') {
        const logoDoan = document.getElementById('hero-logo-doan') as HTMLImageElement;
        const heroTitle = document.getElementById('hero-title-main');
        const heroSection = document.getElementById('section-hero-banner');
        const heroVideo = document.getElementById('hero-bg-video') as HTMLVideoElement;
        const heroOverlay = document.getElementById('hero-video-overlay');

        if (logoDoan && sec.settings?.logo_doan_url) logoDoan.src = sec.settings.logo_doan_url;
        if (heroTitle && sec.custom_title) heroTitle.textContent = sec.custom_title;

        const bgType = sec.settings?.bg_type || (sec.settings?.hero_video_url ? 'video' : 'image');
        const heightMode = sec.settings?.hero_height || 'standard';
        const fitMode = sec.settings?.hero_fit || 'cover';
        const posMode = sec.settings?.hero_position || 'center';

        // Apply responsive height mode
        if (heroSection) {
          heroSection.classList.remove('hero-h-compact', 'hero-h-standard', 'hero-h-cinematic', 'hero-h-16-9');
          if (heightMode === 'compact') heroSection.classList.add('hero-h-compact');
          else if (heightMode === 'cinematic') heroSection.classList.add('hero-h-cinematic');
          else if (heightMode === 'auto_16_9') heroSection.classList.add('hero-h-16-9');
          else heroSection.classList.add('hero-h-standard');
        }

        if (bgType === 'video' && sec.settings?.hero_video_url) {
          if (heroVideo) {
            heroVideo.src = sec.settings.hero_video_url;
            heroVideo.style.objectFit = fitMode;
            heroVideo.style.objectPosition = posMode;
            heroVideo.classList.remove('hidden');
            heroVideo.play().catch(() => {});
          }
          if (heroOverlay) heroOverlay.classList.remove('hidden');
          if (heroSection) {
            heroSection.style.backgroundImage = 'none';
          }
        } else {
          if (heroVideo) {
            heroVideo.classList.add('hidden');
            heroVideo.pause();
          }
          if (heroOverlay) heroOverlay.classList.add('hidden');
          if (heroSection && sec.settings?.hero_bg_url) {
            heroSection.style.backgroundImage = `linear-gradient(180deg, rgba(0, 0, 0, 0.40) 0%, rgba(0, 0, 0, 0.15) 45%, rgba(0, 0, 0, 0.65) 100%), url('${sec.settings.hero_bg_url}')`;
            heroSection.style.backgroundSize = fitMode;
            heroSection.style.backgroundPosition = posMode;
          }
        }
      }

      if (sec.section_code === 'agencies_grid') {
        if (Array.isArray(sec.settings?.selected_ids) && sec.settings.selected_ids.length > 0) {
          this.selectedAgencyIds = sec.settings.selected_ids.map(Number);
          this.renderPortalGrid();
        }
      }

      if (sec.custom_title) {
        const titleId = titleIdMap[sec.section_code] || `title-${sec.section_code}`;
        const titleEl = document.getElementById(titleId) || document.getElementById(`title-${sec.section_code.replace(/_/g, '-')}`);
        if (titleEl) titleEl.textContent = sec.custom_title;
      }

      if (sec.custom_subtitle) {
        const subtitleId = subtitleIdMap[sec.section_code] || `subtitle-${sec.section_code}`;
        const subtitleEl = document.getElementById(subtitleId) || document.getElementById(`subtitle-${sec.section_code.replace(/_/g, '-')}`);
        if (subtitleEl) subtitleEl.textContent = sec.custom_subtitle;
      }
    });

    // Append remaining unprocessed sections in original HTML order
    const allSectionIds = Object.values(codeToIdMap);
    allSectionIds.forEach(id => {
      if (!processedIds.has(id)) {
        const el = document.getElementById(id);
        if (el) parentContainer.appendChild(el);
      }
    });
  }

  private renderStatCardsList(cards: any[]) {
    if (!Array.isArray(cards) || cards.length === 0) return;
    const container = document.getElementById('stats-cards-container');
    if (container) {
      container.innerHTML = cards.map((c: any) => `
        <div class="stat-card">
          <div class="w-11 h-11 sm:w-13 sm:h-13 lg:w-16 lg:h-16 rounded-xl sm:rounded-2xl ${c.bg} ${c.color} flex items-center justify-center text-2xl sm:text-3xl lg:text-4xl font-bold shrink-0">
            <span class="material-symbols-outlined text-2xl sm:text-3xl lg:text-4xl">${c.icon}</span>
          </div>
          <div class="min-w-0 flex-1">
            <b class="text-lg sm:text-2xl md:text-3xl lg:text-4xl xl:text-[2.6rem] font-black text-slate-900 dark:text-white leading-none block truncate">${c.value}</b>
            <span class="text-[11px] sm:text-xs lg:text-sm xl:text-base font-semibold text-slate-500 dark:text-slate-400 stat-label mt-0.5 lg:mt-1">${c.label}</span>
          </div>
        </div>
      `).join('');

      triggerStatCardsCountUp();
    }
  }

  private showPortalTab(tabName: string) {
    const preloadStyle = document.getElementById('map-preload-style');
    if (preloadStyle) preloadStyle.remove();

    const mainView = document.getElementById('portal-main-view');
    const mapContainer = document.getElementById('map-view-container');

    if (tabName === 'map') {
      window.location.hash = '#map-view';
      if (mainView) mainView.classList.add('hidden');
      if (mapContainer) {
        mapContainer.classList.remove('hidden');
        mapContainer.classList.add('flex');
        if (!this.map) {
          this.initLeafletMap();
        } else {
          setTimeout(() => this.map?.invalidateSize(), 200);
        }
      }
    } else {
      if (window.location.hash.includes('map')) {
        history.replaceState(null, '', window.location.pathname);
      }
      if (mapContainer) {
        mapContainer.classList.add('hidden');
        mapContainer.classList.remove('flex');
      }
      if (mainView) mainView.classList.remove('hidden');

      if (tabName === 'procedures') {
        const el = document.getElementById('section-hdsd-procedure');
        if (el) el.scrollIntoView({ behavior: 'smooth' });
      } else if (tabName === 'neighborhoods') {
        const el = document.getElementById('section-neighborhoods');
        if (el) el.scrollIntoView({ behavior: 'smooth' });
      } else if (tabName === 'officials') {
        const el = document.getElementById('section-officials');
        if (el) el.scrollIntoView({ behavior: 'smooth' });
      } else if (tabName === 'meritorious') {
        const el = document.getElementById('section-meritorious');
        if (el) el.scrollIntoView({ behavior: 'smooth' });
      } else {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
    }

    if (typeof (window as any).initSharedHeader === 'function') {
      (window as any).initSharedHeader();
    }
  }

  private renderMapPlacesCarousel(query: string = '') {
    const container = document.getElementById('map-places-carousel');
    const badgeCount = document.getElementById('map-places-count-badge');
    if (!container) return;

    let items = this.places.filter(p => p.category !== 'neighborhood');
    if (query.trim()) {
      const q = query.trim().toLowerCase();
      items = items.filter(p =>
        p.name.toLowerCase().includes(q) ||
        (p.address && p.address.toLowerCase().includes(q)) ||
        (p.former_names && p.former_names.toLowerCase().includes(q))
      );
    }

    if (badgeCount) {
      badgeCount.textContent = String(items.length);
    }

    if (items.length === 0) {
      container.innerHTML = `
        <div class="px-5 py-3 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md rounded-2xl border border-slate-200 dark:border-slate-800 text-xs font-bold text-slate-500 shadow-md">
          Không tìm thấy địa điểm nào khớp với "${query}"
        </div>
      `;
      return;
    }

    container.innerHTML = items.map(p => {
      let badgeText = 'ĐỊA ĐIỂM';
      let badgeColor = 'bg-sky-50 text-sky-700 dark:bg-sky-950/80 dark:text-sky-300 border-sky-200 dark:border-sky-800';
      if (p.category === 'government') {
        badgeText = 'HÀNH CHÍNH';
        badgeColor = 'bg-blue-50 text-blue-700 dark:bg-blue-950/80 dark:text-blue-300 border-blue-200 dark:border-blue-800';
      } else if (p.category === 'police') {
        badgeText = 'CÔNG AN';
        badgeColor = 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/80 dark:text-indigo-300 border-indigo-200 dark:border-indigo-800';
      } else if (p.category === 'health') {
        badgeText = 'Y TẾ';
        badgeColor = 'bg-rose-50 text-rose-700 dark:bg-rose-950/80 dark:text-rose-300 border-rose-200 dark:border-rose-800';
      } else if (p.category === 'school') {
        badgeText = 'TRƯỜNG HỌC';
        badgeColor = 'bg-amber-50 text-amber-700 dark:bg-amber-950/80 dark:text-amber-300 border-amber-200 dark:border-amber-800';
      }

      return `
        <div onclick="window.focusMapPlace(${p.id})"
          class="min-w-[270px] max-w-[310px] bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl p-3 rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-xl flex items-center gap-3 shrink-0 cursor-pointer transition-all hover:scale-[1.02] hover:border-[#1d7fe0] hover:shadow-2xl group select-none">
          <img src="${p.image || 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=200&q=80'}"
            class="w-16 h-16 rounded-xl object-cover shrink-0 shadow-sm border border-slate-100 dark:border-slate-800 group-hover:brightness-105" alt="${p.name}" />
          <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5 space-y-1">
            <div class="flex items-center justify-between gap-1">
              <span class="inline-block text-[10px] font-black uppercase ${badgeColor} border px-2 py-0.5 rounded-md leading-none">${badgeText}</span>
              <button onclick="event.stopPropagation(); window.viewPlaceDetail(${p.id})" class="text-[11px] font-bold text-[#1d7fe0] hover:underline flex items-center gap-0.5">
                <span>Chi tiết</span>
                <span class="material-symbols-outlined text-xs">arrow_forward</span>
              </button>
            </div>
            <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white truncate group-hover:text-[#1d7fe0] transition-colors" title="${p.name}">${p.name}</h4>
            <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400 truncate flex items-center gap-0.5">
              <span class="material-symbols-outlined text-[13px] text-red-500 shrink-0">location_on</span>
              <span class="truncate">${p.address || 'Phường Duy Hà'}</span>
            </p>
          </div>
        </div>
      `;
    }).join('');
  }

  private initSearch() {
    const input = document.getElementById('portal-search-input') as HTMLInputElement;
    const resultsBox = document.getElementById('portal-search-results');
    const searchBtn = document.getElementById('portal-search-btn');

    if (input && resultsBox) {
      input.addEventListener('input', () => {
        const query = input.value.trim().toLowerCase();
        if (query.length === 0) {
          resultsBox.classList.add('hidden');
          resultsBox.innerHTML = '';
          return;
        }

        const filteredPlaces = this.places.filter(p =>
          p.name.toLowerCase().includes(query) ||
          (p.address && p.address.toLowerCase().includes(query)) ||
          (p.former_names && p.former_names.toLowerCase().includes(query)) ||
          (p.description && p.description.toLowerCase().includes(query))
        );

        const filteredOfficials = this.officials.filter(o =>
          o.name.toLowerCase().includes(query) ||
          o.role.toLowerCase().includes(query) ||
          o.phone.includes(query)
        );

        if (filteredPlaces.length === 0 && filteredOfficials.length === 0) {
          resultsBox.innerHTML = `
            <div class="p-4 text-center text-xs text-slate-400">
              Không tìm thấy kết quả khớp với "${query}"
            </div>
          `;
        } else {
          let html = '';
          if (filteredPlaces.length > 0) {
            html += `<div class="p-2 text-[10px] font-extrabold uppercase text-blue-600 tracking-wider bg-slate-50 dark:bg-slate-800">Địa điểm & Tổ dân phố</div>`;
            filteredPlaces.forEach(p => {
              html += `
                <div onclick="window.viewPlaceDetail(${p.id})" class="p-3 hover:bg-blue-50 dark:hover:bg-slate-800 cursor-pointer transition-colors border-b border-slate-100 dark:border-slate-800/50 flex items-center justify-between">
                  <div>
                    <b class="text-xs font-bold text-slate-900 dark:text-white block">${p.name}</b>
                    <small class="text-[11px] text-slate-500">${p.former_names ? `Sáp nhập: ${p.former_names}` : (p.address || 'Phường Duy Hà')}</small>
                  </div>
                  <span class="material-symbols-outlined text-blue-600 text-sm">info</span>
                </div>
              `;
            });
          }

          if (filteredOfficials.length > 0) {
            html += `<div class="p-2 text-[10px] font-extrabold uppercase text-emerald-600 tracking-wider bg-slate-50 dark:bg-slate-800">Cán bộ phụ trách</div>`;
            filteredOfficials.forEach(o => {
              html += `
                <div onclick="window.location.href='tel:${o.phone}'" class="p-3 hover:bg-emerald-50 dark:hover:bg-slate-800 cursor-pointer transition-colors border-b border-slate-100 dark:border-slate-800/50 flex items-center justify-between">
                  <div>
                    <b class="text-xs font-bold text-slate-900 dark:text-white block">${o.name} - ${o.role}</b>
                    <small class="text-[11px] text-emerald-600 font-bold">${o.phone}</small>
                  </div>
                  <span class="material-symbols-outlined text-emerald-600 text-sm">call</span>
                </div>
              `;
            });
          }
          resultsBox.innerHTML = html;
        }

        resultsBox.classList.remove('hidden');
      });

      if (searchBtn) {
        searchBtn.addEventListener('click', () => {
          const query = input.value.trim().toLowerCase();
          if (query) {
            const match = this.places.find(p => p.name.toLowerCase().includes(query));
            if (match) {
              this.viewPlaceDetail(match.id);
            }
          }
        });
      }
    }
  }

  private filterPortalCategory(cat: string) {
    this.activeCategory = cat;

    document.querySelectorAll('.cat-filter-btn').forEach(btn => {
      const category = btn.getAttribute('data-cat');
      if (category === cat) {
        btn.className = 'cat-filter-btn px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all bg-[#3399fe] text-white shadow-sm';
      } else {
        btn.className = 'cat-filter-btn px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all text-slate-600 dark:text-slate-300 hover:text-[#3399fe]';
      }
    });

    this.renderPortalGrid();
  }

  private openAllAgenciesModal() {
    const modal = document.getElementById('all-agencies-modal');
    if (modal) {
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      this.renderAllAgenciesTable();
    }
  }

  private closeAllAgenciesModal() {
    const modal = document.getElementById('all-agencies-modal');
    if (modal) {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }
  }

  private renderAllAgenciesTable() {
    const tbody = document.getElementById('all-agencies-table-body');
    const badge = document.getElementById('all-agencies-count-badge');
    const searchInput = document.getElementById('all-agencies-search-input') as HTMLInputElement;

    if (!tbody) return;

    const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
    let agencies = this.places.filter(p => p.category !== 'neighborhood');

    if (query) {
      agencies = agencies.filter(p =>
        p.name.toLowerCase().includes(query) ||
        (p.address && p.address.toLowerCase().includes(query)) ||
        (p.phone && p.phone.includes(query))
      );
    }

    if (badge) badge.textContent = agencies.length.toString();

    if (agencies.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="6" class="p-8 text-center text-slate-400 font-semibold">
            Không tìm thấy cơ quan hay đơn vị công lập khớp với từ khóa "${query}".
          </td>
        </tr>
      `;
      return;
    }

    tbody.innerHTML = agencies.map((p, idx) => {
      const isPolice = p.category === 'police' || p.name.toLowerCase().includes('công an');
      const isHealth = p.category === 'health' || p.name.toLowerCase().includes('bệnh viện') || p.name.toLowerCase().includes('trạm y tế') || p.name.toLowerCase().includes('y tế') || p.name.toLowerCase().includes('phòng khám');
      const isSchool = p.category === 'school' || p.name.toLowerCase().includes('trường') || p.name.toLowerCase().includes('mầm non') || p.name.toLowerCase().includes('tiểu học') || p.name.toLowerCase().includes('thcs') || p.name.toLowerCase().includes('thpt');
      const isGov = p.category === 'government' || p.name.toLowerCase().includes('ubnd') || p.name.toLowerCase().includes('ủy ban') || p.name.toLowerCase().includes('đảng') || p.name.toLowerCase().includes('mặt trận') || p.name.toLowerCase().includes('hđnd');

      let badgeText = 'HÀNH CHÍNH';
      let badgeColor = 'bg-red-50 text-red-700 dark:bg-red-950/80 dark:text-red-300 border-red-200 dark:border-red-900/60';
      if (isPolice) {
        badgeText = 'AN NINH & TRẬT TỰ';
        badgeColor = 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/80 dark:text-indigo-300 border-indigo-200 dark:border-indigo-900/60';
      } else if (isHealth) {
        badgeText = 'Y TẾ & SỨC KHỎE';
        badgeColor = 'bg-rose-50 text-rose-700 dark:bg-rose-950/80 dark:text-rose-300 border-rose-200 dark:border-rose-900/60';
      } else if (isSchool) {
        badgeText = 'GIÁO DỤC & ĐÀO TẠO';
        badgeColor = 'bg-amber-50 text-amber-700 dark:bg-amber-950/80 dark:text-amber-300 border-amber-200 dark:border-amber-900/60';
      } else if (isGov) {
        badgeText = 'CƠ QUAN HÀNH CHÍNH';
        badgeColor = 'bg-red-50 text-red-700 dark:bg-red-950/80 dark:text-red-300 border-red-200 dark:border-red-900/60';
      } else {
        badgeText = 'ĐƠN VỊ CÔNG LẬP';
        badgeColor = 'bg-sky-50 text-sky-700 dark:bg-sky-950/80 dark:text-sky-300 border-sky-200 dark:border-sky-900/60';
      }

      const phone = p.phone || '';
      const directionsUrl = (p.lat && p.lng)
        ? `https://www.google.com/maps/dir/?api=1&destination=${p.lat},${p.lng}`
        : `https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(p.name + ' Phường Duy Hà')}`;

      const imgSrc = p.image || '/hero-bg.jpg';
      const escapedName = (p.name || '').replace(/'/g, "\\'");

      return `
        <tr class="odd:bg-white even:bg-slate-50/70 hover:bg-sky-50/60 dark:odd:bg-slate-900 dark:even:bg-slate-800/60 dark:hover:bg-slate-800 transition-colors">
          <td class="py-3 px-3 text-center font-bold text-slate-500 border-r border-slate-200 dark:border-slate-800">${idx + 1}</td>
          <td class="py-2.5 px-3 text-center border-r border-slate-200 dark:border-slate-800">
            <div onclick="window.openAgencyImageModal && window.openAgencyImageModal('${imgSrc}', '${escapedName}')"
              title="Nhấn để xem ảnh phóng to"
              class="w-16 h-12 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm mx-auto bg-slate-100 dark:bg-slate-800 flex items-center justify-center cursor-pointer group/img relative hover:ring-2 hover:ring-blue-500 hover:shadow-md transition-all">
              <img src="${imgSrc}" alt="${p.name}" class="w-full h-full object-cover group-hover/img:scale-110 transition-transform duration-300" onerror="this.onerror=null;this.src='/hero-bg.jpg';" />
              <div class="absolute inset-0 bg-slate-900/30 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-lg drop-shadow">zoom_in</span>
              </div>
            </div>
          </td>
          <td class="py-3 px-4 border-r border-slate-200 dark:border-slate-800">
            <span class="inline-block text-[11px] font-black uppercase ${badgeColor} border px-2.5 py-1 rounded-lg tracking-wider">${badgeText}</span>
          </td>
          <td class="py-3 px-4 border-r border-slate-200 dark:border-slate-800">
            <b class="text-sm font-extrabold text-slate-900 dark:text-white block">${p.name}</b>
          </td>
          <td class="py-3 px-4 border-r border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300">
            <div class="flex items-center gap-1.5">
              <span class="material-symbols-outlined text-sm text-red-600 shrink-0">location_on</span>
              <span>${p.address || 'Phường Duy Hà, Ninh Bình'}</span>
            </div>
          </td>
          <td class="py-3 px-4 text-center border-r border-slate-200 dark:border-slate-800">
            ${phone ? `
              <a href="tel:${phone}" class="inline-flex items-center gap-1 font-extrabold text-blue-600 dark:text-sky-400 hover:underline">
                <span class="material-symbols-outlined text-base">call</span>
                <span>${phone}</span>
              </a>
            ` : `<span class="text-slate-400 font-semibold">--</span>`}
          </td>
          <td class="py-3 px-4 text-center">
            <a href="${directionsUrl}" target="_blank" class="inline-flex items-center gap-1 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm hover:shadow-md active:scale-95 whitespace-nowrap">
              <span class="material-symbols-outlined text-base">directions</span>
              <span>Chỉ đường</span>
            </a>
          </td>
        </tr>
      `;
    }).join('');
  }

  private renderPortalGrid() {
    const grid = document.getElementById('portal-places-grid');
    if (!grid) return;

    let items = this.places.filter(p => p.category !== 'neighborhood');
    if (this.activeCategory === 'tdp_new') {
      items = this.places.filter(p => p.category === 'neighborhood' && p.status === 'active');
    } else if (this.activeCategory === 'tdp_old') {
      items = this.places.filter(p => p.category === 'neighborhood' && p.status === 'closed');
    } else if (this.activeCategory !== 'all') {
      items = this.places.filter(p => p.category === this.activeCategory);
    }

    if (items.length === 0) {
      grid.innerHTML = `
        <div class="col-span-full p-12 text-center text-slate-400 border border-dashed border-slate-200 dark:border-slate-800 rounded-3xl">
          Chưa có dữ liệu địa điểm theo phân loại này.
        </div>
      `;
      return;
    }

    // Hiển thị đúng 4 cơ quan tùy chọn theo cấu hình Admin (nếu có) hoặc 4 cơ quan đầu tiên
    let displayItems: Place[] = [];
    if (this.activeCategory === 'all' && this.selectedAgencyIds && this.selectedAgencyIds.length > 0) {
      const customPicked: Place[] = [];
      this.selectedAgencyIds.forEach(id => {
        const found = this.places.find(p => p.id === id);
        if (found) customPicked.push(found);
      });
      if (customPicked.length > 0) {
        const pickedSet = new Set(customPicked.map(p => p.id));
        items.forEach(p => {
          if (customPicked.length < 4 && !pickedSet.has(p.id)) {
            customPicked.push(p);
            pickedSet.add(p.id);
          }
        });
        displayItems = customPicked.slice(0, 4);
      } else {
        displayItems = items.slice(0, 4);
      }
    } else {
      displayItems = items.slice(0, 4);
    }

    grid.innerHTML = displayItems.map(p => {
      const isPolice = p.category === 'police' || p.name.toLowerCase().includes('công an');
      const isHealth = p.category === 'health' || p.name.toLowerCase().includes('bệnh viện') || p.name.toLowerCase().includes('trạm y tế') || p.name.toLowerCase().includes('y tế') || p.name.toLowerCase().includes('phòng khám');
      const isSchool = p.category === 'school' || p.name.toLowerCase().includes('trường') || p.name.toLowerCase().includes('mầm non') || p.name.toLowerCase().includes('tiểu học') || p.name.toLowerCase().includes('thcs') || p.name.toLowerCase().includes('thpt');
      const isTdp = p.category === 'neighborhood' || p.name.toLowerCase().includes('nhà văn hóa') || p.name.toLowerCase().includes('tổ dân phố');
      const isGov = p.category === 'government' || p.name.toLowerCase().includes('ubnd') || p.name.toLowerCase().includes('ủy ban') || p.name.toLowerCase().includes('đảng') || p.name.toLowerCase().includes('mặt trận') || p.name.toLowerCase().includes('hđnd');

      let subTitle = 'CƠ QUAN HÀNH CHÍNH';
      let iconName = 'corporate_fare';

      if (isPolice) {
        subTitle = 'AN NINH & TRẬT TỰ XÃ HỘI';
        iconName = 'local_police';
      } else if (isHealth) {
        subTitle = 'Y TẾ & CHĂM SÓC SỨC KHỎE';
        iconName = 'local_hospital';
      } else if (isSchool) {
        subTitle = 'GIÁO DỤC & ĐÀO TẠO';
        iconName = 'school';
      } else if (isTdp) {
        subTitle = 'NHÀ VĂN HÓA & ĐỊA BÀN DÂN CƯ';
        iconName = 'home_work';
      } else if (isGov) {
        subTitle = 'CƠ QUAN HÀNH CHÍNH';
        iconName = 'corporate_fare';
      } else {
        subTitle = 'CƠ QUAN & ĐƠN VỊ CÔNG LẬP';
        iconName = 'location_city';
      }

      const placeImg = formatStorageUrl(p.image);

      // Chỉ lấy số điện thoại khi có trong dữ liệu nhập của Admin hoặc số trực ban cơ quan đặc thù
      const phone = p.phone || '';

      const directionsUrl = (p.lat && p.lng)
        ? `https://www.google.com/maps/dir/?api=1&destination=${p.lat},${p.lng}`
        : `https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(p.name + ' Phường Duy Hà')}`;

      return `
        <div onclick="window.location.href='/agencies.html'"
          class="relative rounded-3xl overflow-hidden shadow-xl min-h-[270px] sm:min-h-[290px] flex flex-col justify-between p-6 sm:p-8 border border-amber-400/20 dark:border-slate-800 group text-white cursor-pointer transition-all duration-300 hover:shadow-2xl hover:scale-[1.01]">
          <!-- Background Image with Soft Gradient Overlay -->
          <img src="${placeImg}"
            alt="${p.name}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
          <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/35 to-slate-950/10"></div>

          <!-- Card Top Header -->
          <div class="relative z-10 flex items-center gap-3.5">
            <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white text-2xl sm:text-3xl font-bold border border-white/30 shrink-0">
              <span class="material-symbols-outlined">${iconName}</span>
            </div>
            <div>
              <span class="text-xs font-black uppercase tracking-wider text-amber-300 block">
                ${subTitle}
              </span>
              <h3 class="text-xl sm:text-2xl font-black text-white drop-shadow-md leading-tight mt-0.5">${p.name}</h3>
            </div>
          </div>

          <!-- Card Bottom Actions (2 nút: Hotline & Trực ban + Chỉ đường) -->
          <div class="relative z-10 flex items-stretch gap-2.5 mt-6">
            ${phone ? `
              <a href="tel:${phone}" onclick="event.stopPropagation()"
                class="flex-1 bg-white hover:bg-slate-100 text-slate-900 rounded-xl py-3 px-3.5 font-extrabold text-xs sm:text-sm transition-all flex items-center justify-center gap-1.5 shadow-md active:scale-95 whitespace-nowrap">
                <span class="material-symbols-outlined text-base sm:text-lg text-blue-700">call</span>
                <span>Hotline & Trực ban</span>
              </a>
            ` : ''}
            <a href="${directionsUrl}" target="_blank" onclick="event.stopPropagation()"
              class="flex-1 bg-white/25 hover:bg-white/35 text-white rounded-xl py-3 px-3.5 font-extrabold text-xs sm:text-sm transition-all flex items-center justify-center gap-1.5 backdrop-blur-md border border-white/30 shadow-md active:scale-95 whitespace-nowrap">
              <span class="material-symbols-outlined text-base sm:text-lg">directions</span>
              <span>Chỉ đường</span>
            </a>
          </div>
        </div>
      `;
    }).join('');
  }

  private renderTdpModalTables() {
    const oldBody = document.getElementById('old-neighborhoods-list');
    const newBody = document.getElementById('new-neighborhoods-list');
    const oldTitle = document.getElementById('old-neighborhoods-count-title');
    const newTitle = document.getElementById('new-neighborhoods-count-title');
    const oldTotalHouseholdsEl = document.getElementById('old-total-households');
    const oldTotalPeopleEl = document.getElementById('old-total-people');
    const newTotalHouseholdsEl = document.getElementById('new-total-households');
    const newTotalPeopleEl = document.getElementById('new-total-people');

    // Page Section Elements
    const pageOldBody = document.getElementById('page-old-neighborhoods-list');
    const pageNewBody = document.getElementById('page-new-neighborhoods-list');
    const pageOldTitle = document.getElementById('page-old-neighborhoods-count-title');
    const pageNewTitle = document.getElementById('page-new-neighborhoods-count-title');
    const pageOldTotalHouseholdsEl = document.getElementById('page-old-total-households');
    const pageOldTotalPeopleEl = document.getElementById('page-old-total-people');
    const pageNewTotalHouseholdsEl = document.getElementById('page-new-total-households');
    const pageNewTotalPeopleEl = document.getElementById('page-new-total-people');

    const groupStyles: Record<string, { label: string; badgeClass: string; highlightClass: string }> = {
      'bach-xa': { label: 'TDP Bạch Xá', badgeClass: 'bg-orange-50 text-orange-800 border-orange-200 dark:bg-orange-950/60 dark:text-orange-300 dark:border-orange-800', highlightClass: '' },
      'chuong': { label: 'TDP Chuồng', badgeClass: 'bg-amber-50 text-amber-800 border-amber-300 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-800', highlightClass: '' },
      'duy-hai': { label: 'TDP Duy Hải', badgeClass: 'bg-emerald-50 text-emerald-800 border-emerald-300 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800', highlightClass: '' },
      'duy-minh': { label: 'TDP Duy Minh', badgeClass: 'bg-indigo-50 text-indigo-800 border-indigo-200 dark:bg-indigo-950/60 dark:text-indigo-300 dark:border-indigo-800', highlightClass: '' },
      'dong-hai': { label: 'TDP Đông Hải', badgeClass: 'bg-blue-50 text-blue-800 border-blue-200 dark:bg-blue-950/60 dark:text-blue-300 dark:border-blue-800', highlightClass: '' },
      'dong-linh-trang': { label: 'TDP Động Linh Trang', badgeClass: 'bg-purple-50 text-purple-800 border-purple-200 dark:bg-purple-950/60 dark:text-purple-300 dark:border-purple-800', highlightClass: '' },
      'hoang-dong': { label: 'TDP Hoàng Đồng', badgeClass: 'bg-sky-50 text-sky-800 border-sky-300 dark:bg-sky-950/60 dark:text-sky-300 dark:border-sky-800', highlightClass: '' },
      'huong-cat': { label: 'TDP Hương Cát', badgeClass: 'bg-teal-50 text-teal-800 border-teal-200 dark:bg-teal-950/60 dark:text-teal-300 dark:border-teal-800', highlightClass: '' },
      'ngoc-dong': { label: 'TDP Ngọc Động', badgeClass: 'bg-rose-50 text-rose-800 border-rose-200 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-800', highlightClass: '' },
      'ngoc-tu': { label: 'TDP Ngọc Tú', badgeClass: 'bg-red-50 text-red-800 border-red-200 dark:bg-red-950/60 dark:text-red-300 dark:border-red-800', highlightClass: '' },
    };

    // Helper to extract sortable name without prefix
    const getCleanName = (name: string) => (name || '').replace(/^(TDP|Tổ dân phố)\s+/gi, '').trim();

    // 1. Sort New TDPs alphabetically (A-Z)
    const newList = this.neighborhoods
      .filter(n => n.type === 'new')
      .sort((a, b) => getCleanName(a.name).localeCompare(getCleanName(b.name), 'vi', { numeric: true, sensitivity: 'base' }));

    // 2. Map each group_code to its sorted index in newList
    const groupOrderMap = new Map<string, number>();
    newList.forEach((item, idx) => {
      groupOrderMap.set(item.group_code, idx);
      if (groupStyles[item.group_code]) {
        groupStyles[item.group_code].label = `TDP ${getCleanName(item.name)}`;
      }
    });

    // 3. Sort Old TDPs corresponding to the sorted New TDPs, and alphabetically within the same group
    const oldList = this.neighborhoods
      .filter(n => n.type === 'old')
      .sort((a, b) => {
        const orderA = groupOrderMap.has(a.group_code) ? groupOrderMap.get(a.group_code)! : 999;
        const orderB = groupOrderMap.has(b.group_code) ? groupOrderMap.get(b.group_code)! : 999;
        if (orderA !== orderB) {
          return orderA - orderB;
        }
        return getCleanName(a.name).localeCompare(getCleanName(b.name), 'vi', { numeric: true, sensitivity: 'base' });
      });

    const oldTitleText = `HIỆN TRẠNG TỔ DÂN PHỐ (${oldList.length} TỔ DÂN PHỐ - TRƯỚC SÁP NHẬP)`;
    const newTitleText = `DỰ KIẾN PHƯƠNG ÁN SẮP XẾP (${newList.length} TỔ DÂN PHỐ - SAU SÁP NHẬP)`;

    if (oldTitle) oldTitle.textContent = oldTitleText;
    if (newTitle) newTitle.textContent = newTitleText;
    if (pageOldTitle) pageOldTitle.textContent = oldTitleText;
    if (pageNewTitle) pageNewTitle.textContent = newTitleText;

    let oldTotalHouseholds = 0;
    let oldTotalPeople = 0;
    let oldTotalArea = 0;
    const oldRowsHtml = oldList.map((n, idx) => {
      oldTotalHouseholds += n.households || 0;
      oldTotalPeople += n.people || 0;
      oldTotalArea += n.area_ha || 0;
      const gStyle = groupStyles[n.group_code] || { label: 'TDP Mới', badgeClass: 'bg-slate-100 text-slate-700', borderClass: 'border-l-4 border-slate-400', highlightClass: '' };
      return `
        <tr data-group-code="${n.group_code}" class="tdp-merger-row transition-all duration-300 cursor-pointer hover:bg-amber-50/80 dark:hover:bg-slate-800/80">
          <td class="py-2 px-1.5 text-center text-slate-400 font-bold">${idx + 1}</td>
          <td class="py-2 px-1.5">
            <div class="flex items-center gap-1.5 flex-wrap">
              <span class="text-xs font-bold text-slate-800 dark:text-slate-100 whitespace-nowrap">TDP ${n.name.replace(/^(TDP|Tổ dân phố)\s+/gi, '').trim()}</span>
              <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[10.5px] font-bold border ${gStyle.badgeClass} whitespace-nowrap shadow-2xs">
                <span class="material-symbols-outlined text-[12px] opacity-75">arrow_right_alt</span>
                <span>${gStyle.label}</span>
              </span>
            </div>
          </td>
          <td class="py-2 px-1.5 text-right font-bold text-slate-800 dark:text-slate-200">${n.households.toLocaleString('vi-VN')}</td>
          <td class="py-2 px-1.5 text-right font-bold text-slate-800 dark:text-slate-200">${n.people.toLocaleString('vi-VN')}</td>
          <td class="py-2 px-1.5 text-right font-bold text-slate-900 dark:text-slate-100">${n.area_ha ? n.area_ha.toFixed(2).replace('.', ',') : '--'}</td>
        </tr>
      `;
    }).join('');

    if (oldBody) oldBody.innerHTML = oldRowsHtml;
    if (pageOldBody) pageOldBody.innerHTML = oldRowsHtml;

    const oldHHFormatted = oldTotalHouseholds.toLocaleString('vi-VN');
    const oldPeopleFormatted = oldTotalPeople.toLocaleString('vi-VN');
    const oldAreaFormatted = oldTotalArea.toFixed(2).replace('.', ',');
    if (oldTotalHouseholdsEl) oldTotalHouseholdsEl.textContent = oldHHFormatted;
    if (oldTotalPeopleEl) oldTotalPeopleEl.textContent = oldPeopleFormatted;
    if (pageOldTotalHouseholdsEl) pageOldTotalHouseholdsEl.textContent = oldHHFormatted;
    if (pageOldTotalPeopleEl) pageOldTotalPeopleEl.textContent = oldPeopleFormatted;
    const modalOldTotalAreaEl = document.getElementById('old-total-area');
    if (modalOldTotalAreaEl) modalOldTotalAreaEl.textContent = oldAreaFormatted;
    const pageOldTotalAreaEl = document.getElementById('page-old-total-area');
    if (pageOldTotalAreaEl) pageOldTotalAreaEl.textContent = oldAreaFormatted;

    let newTotalHouseholds = 0;
    let newTotalPeople = 0;
    let newTotalArea = 0;
    const newRowsHtml = newList.map((n, idx) => {
      const area = n.area_ha || oldList.filter(o => o.group_code === n.group_code).reduce((sum, o) => sum + (o.area_ha || 0), 0);
      newTotalHouseholds += n.households || 0;
      newTotalPeople += n.people || 0;
      newTotalArea += area;
      const gStyle = groupStyles[n.group_code] || { label: 'TDP Mới', badgeClass: 'bg-slate-100 text-slate-700', borderClass: 'border-l-4 border-slate-400', highlightClass: '' };
      return `
        <tr data-group-code="${n.group_code}" class="tdp-merger-row transition-all duration-300 cursor-pointer hover:bg-amber-50/80 dark:hover:bg-slate-800/80">
          <td class="py-2 px-1.5 text-center text-slate-400 font-bold">${idx + 1}</td>
          <td class="py-2 px-1.5">
            <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded text-xs font-bold border ${gStyle.badgeClass} whitespace-nowrap shadow-2xs">
              ${gStyle.label}
            </span>
          </td>
          <td class="py-2 px-1.5 text-right font-bold text-slate-800 dark:text-slate-200">${n.households.toLocaleString('vi-VN')}</td>
          <td class="py-2 px-1.5 text-right font-bold text-slate-800 dark:text-slate-200">${n.people.toLocaleString('vi-VN')}</td>
          <td class="py-2 px-1.5 text-right font-bold text-slate-900 dark:text-slate-100">${area ? area.toFixed(2).replace('.', ',') : '--'}</td>
          <td class="py-2 px-1.5 text-center">
            <button onclick="event.stopPropagation(); window.openAllOfficialsModalForTdp('${n.name.replace('TDP ', '')}')" class="inline-flex items-center justify-center px-2 py-0.5 bg-sky-50 dark:bg-sky-950/50 hover:bg-[#3399fe] text-[#1d7fe0] dark:text-sky-300 hover:text-white text-xs font-bold rounded border border-sky-300/60 dark:border-sky-800 transition-all shadow-2xs active:scale-95 whitespace-nowrap" title="Xem danh sách cán bộ ${n.name}">
              <span>Chi tiết</span>
            </button>
          </td>
        </tr>
      `;
    }).join('');

    if (newBody) newBody.innerHTML = newRowsHtml;
    if (pageNewBody) pageNewBody.innerHTML = newRowsHtml;

    const newHHFormatted = newTotalHouseholds.toLocaleString('vi-VN');
    const newPeopleFormatted = newTotalPeople.toLocaleString('vi-VN');
    const newAreaFormatted = newTotalArea.toFixed(2).replace('.', ',');
    if (newTotalHouseholdsEl) newTotalHouseholdsEl.textContent = newHHFormatted;
    if (newTotalPeopleEl) newTotalPeopleEl.textContent = newPeopleFormatted;
    if (pageNewTotalHouseholdsEl) pageNewTotalHouseholdsEl.textContent = newHHFormatted;
    if (pageNewTotalPeopleEl) pageNewTotalPeopleEl.textContent = newPeopleFormatted;
    const modalNewTotalAreaEl = document.getElementById('new-total-area');
    if (modalNewTotalAreaEl) modalNewTotalAreaEl.textContent = newAreaFormatted;
    const pageNewTotalAreaEl = document.getElementById('page-new-total-area');
    if (pageNewTotalAreaEl) pageNewTotalAreaEl.textContent = newAreaFormatted;

    // Attach Hover Highlight & Dimming Events
    this.initTdpHoverHighlight();
  }

  private initTdpHoverHighlight() {
    const rows = document.querySelectorAll<HTMLElement>('.tdp-merger-row');
    let currentGroup: string | null = null;

    const highlightGroup = (groupCode: string | null) => {
      currentGroup = groupCode;
      rows.forEach(r => {
        const rCode = r.getAttribute('data-group-code');
        if (!groupCode) {
          r.classList.remove('bg-amber-100/90', 'dark:bg-amber-950/80', 'font-black', 'shadow-md', 'ring-2', 'ring-amber-500', 'z-10', 'opacity-30', 'grayscale-[50%]');
        } else if (rCode === groupCode) {
          r.classList.add('bg-amber-100/90', 'dark:bg-amber-950/80', 'font-black', 'shadow-md', 'ring-2', 'ring-amber-500', 'z-10');
          r.classList.remove('opacity-30', 'grayscale-[50%]');
        } else {
          r.classList.add('opacity-30', 'grayscale-[50%]');
          r.classList.remove('bg-amber-100/90', 'dark:bg-amber-950/80', 'font-black', 'shadow-md', 'ring-2', 'ring-amber-500', 'z-10');
        }
      });
    };

    rows.forEach(row => {
      // Desktop Hover
      row.addEventListener('mouseenter', () => {
        if ('ontouchstart' in window && window.innerWidth < 1024) return;
        const groupCode = row.getAttribute('data-group-code');
        if (groupCode) highlightGroup(groupCode);
      });

      row.addEventListener('mouseleave', () => {
        if ('ontouchstart' in window && window.innerWidth < 1024) return;
        highlightGroup(null);
      });

      // Touch / Click on Mobile & Desktop
      row.addEventListener('click', (e) => {
        e.stopPropagation();
        const groupCode = row.getAttribute('data-group-code');
        if (!groupCode) return;

        if (currentGroup === groupCode) {
          highlightGroup(null);
        } else {
          highlightGroup(groupCode);

          // On mobile/tablet screens (< 1024px) where tables stack, scroll partner into view
          if (window.innerWidth < 1024) {
            const partnerRow = Array.from(rows).find(r => r !== row && r.getAttribute('data-group-code') === groupCode);
            if (partnerRow) {
              setTimeout(() => {
                partnerRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
              }, 120);
            }
          }
        }
      });
    });

    // Reset highlight when clicking outside table rows
    document.addEventListener('click', (e) => {
      const target = e.target as HTMLElement;
      if (currentGroup && !target.closest('.tdp-merger-row')) {
        highlightGroup(null);
      }
    });
  }

  private openTdpModal() {
    window.location.href = '/tdp-merger.html';
  }

  private closeTdpModal() {
    const modal = document.getElementById('tdp-modal');
    if (modal) {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }
  }

  private openAllOfficialsModal() {
    this.renderAllOfficialsTable();
    const modal = document.getElementById('all-officials-modal');
    if (modal) {
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }
  }

  private openAllOfficialsModalForTdp(tdpName: string) {
    this.renderAllOfficialsTable(tdpName);
    const modal = document.getElementById('all-officials-modal');
    if (modal) {
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }
  }

  private closeAllOfficialsModal() {
    const modal = document.getElementById('all-officials-modal');
    if (modal) {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }
  }

  private renderAllOfficialsTable(keyword: string = '') {
    const tbody = document.getElementById('all-officials-table-body');
    if (!tbody) return;

    const kw = keyword.toLowerCase().trim();
    const filtered = ALL_TDP_OFFICIALS.filter(item => {
      if (!kw) return true;
      return (
        item.tdp.toLowerCase().includes(kw) ||
        item.biThuName.toLowerCase().includes(kw) ||
        (item.biThuPhone && item.biThuPhone.includes(kw)) ||
        item.toTruongName.toLowerCase().includes(kw) ||
        (item.toTruongPhone && item.toTruongPhone.includes(kw)) ||
        (item.cskvName && item.cskvName.toLowerCase().includes(kw)) ||
        (item.cskvPhone && item.cskvPhone.includes(kw)) ||
        item.matTanName.toLowerCase().includes(kw) ||
        (item.matTanPhone && item.matTanPhone.includes(kw)) ||
        item.nguoiCaoTuoi.toLowerCase().includes(kw) ||
        item.phuNu.toLowerCase().includes(kw) ||
        item.nongDan.toLowerCase().includes(kw) ||
        item.ccb.toLowerCase().includes(kw) ||
        item.doanThanhNien.toLowerCase().includes(kw)
      );
    });

    if (filtered.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="10" class="py-8 text-center text-slate-400 italic">Không tìm thấy cán bộ phù hợp với từ khóa "${keyword}".</td>
        </tr>
      `;
      return;
    }

    tbody.innerHTML = filtered.map(item => {
      const cskvInfo = CSKV_MAP[item.tdp.toLowerCase().trim()] || { name: '', phone: '' };
      const cskvName = item.cskvName || (item as any).cskv_name || cskvInfo.name || '';
      const cskvPhone = item.cskvPhone || (item as any).cskv_phone || cskvInfo.phone || '';

      return `
        <tr class="hover:bg-sky-50/80 dark:hover:bg-slate-800/90 transition-colors border-b border-slate-300 dark:border-slate-700 even:bg-slate-50/60 dark:even:bg-slate-800/30">
          <td class="py-3.5 px-3 font-extrabold text-[#1d7fe0] dark:text-sky-300 border-r border-slate-300 dark:border-slate-700 bg-sky-50/50 dark:bg-slate-800/60 text-sm sm:text-base">${item.tdp}</td>
          <td class="py-3.5 px-3 border-r border-slate-300 dark:border-slate-700">
            <div class="font-extrabold text-slate-900 dark:text-white text-sm sm:text-base">${item.biThuName}</div>
            ${item.biThuPhone ? `<a href="tel:${item.biThuPhone.replace(/\s+/g, '')}" class="text-xs sm:text-sm font-bold text-sky-600 dark:text-sky-400 hover:underline flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-sm">call</span>${item.biThuPhone}</a>` : ''}
          </td>
          <td class="py-3.5 px-3 border-r border-slate-300 dark:border-slate-700">
            <div class="font-extrabold text-slate-900 dark:text-white text-sm sm:text-base">${item.toTruongName}</div>
            ${item.toTruongPhone ? `<a href="tel:${item.toTruongPhone.replace(/\s+/g, '')}" class="text-xs sm:text-sm font-bold text-sky-600 dark:text-sky-400 hover:underline flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-sm">call</span>${item.toTruongPhone}</a>` : ''}
          </td>
          <td class="py-3.5 px-3 border-r border-slate-300 dark:border-slate-700">
            <div class="font-extrabold text-indigo-900 dark:text-indigo-300 text-sm sm:text-base">${cskvName || '--'}</div>
            ${cskvPhone ? `<a href="tel:${cskvPhone.replace(/\s+/g, '')}" class="text-xs sm:text-sm font-bold text-indigo-700 dark:text-indigo-400 hover:underline flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-sm">call</span>${cskvPhone}</a>` : ''}
          </td>
          <td class="py-3.5 px-3 border-r border-slate-300 dark:border-slate-700">
            <div class="font-extrabold text-slate-900 dark:text-white text-sm sm:text-base">${item.matTanName}</div>
            ${item.matTanPhone ? `<a href="tel:${item.matTanPhone.replace(/\s+/g, '')}" class="text-xs sm:text-sm font-bold text-sky-600 dark:text-sky-400 hover:underline flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-sm">call</span>${item.matTanPhone}</a>` : ''}
          </td>
          <td class="py-3.5 px-3 border-r border-slate-300 dark:border-slate-700">
            <div class="font-semibold text-slate-800 dark:text-slate-200 text-sm sm:text-[15px]">${item.nguoiCaoTuoi}</div>
            ${(item as any).nguoiCaoTuoiPhone ? `<a href="tel:${(item as any).nguoiCaoTuoiPhone.replace(/\s+/g, '')}" class="text-xs font-bold text-blue-700 dark:text-blue-400 hover:underline flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-xs">call</span>${(item as any).nguoiCaoTuoiPhone}</a>` : ''}
          </td>
          <td class="py-3.5 px-3 border-r border-slate-300 dark:border-slate-700">
            <div class="font-semibold text-slate-800 dark:text-slate-200 text-sm sm:text-[15px]">${item.phuNu}</div>
            ${(item as any).phuNuPhone ? `<a href="tel:${(item as any).phuNuPhone.replace(/\s+/g, '')}" class="text-xs font-bold text-blue-700 dark:text-blue-400 hover:underline flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-xs">call</span>${(item as any).phuNuPhone}</a>` : ''}
          </td>
          <td class="py-3.5 px-3 border-r border-slate-300 dark:border-slate-700">
            <div class="font-semibold text-slate-800 dark:text-slate-200 text-sm sm:text-[15px]">${item.nongDan}</div>
            ${(item as any).nongDanPhone ? `<a href="tel:${(item as any).nongDanPhone.replace(/\s+/g, '')}" class="text-xs font-bold text-blue-700 dark:text-blue-400 hover:underline flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-xs">call</span>${(item as any).nongDanPhone}</a>` : ''}
          </td>
          <td class="py-3.5 px-3 border-r border-slate-300 dark:border-slate-700">
            <div class="font-semibold text-slate-800 dark:text-slate-200 text-sm sm:text-[15px]">${item.ccb}</div>
            ${(item as any).ccbPhone ? `<a href="tel:${(item as any).ccbPhone.replace(/\s+/g, '')}" class="text-xs font-bold text-blue-700 dark:text-blue-400 hover:underline flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-xs">call</span>${(item as any).ccbPhone}</a>` : ''}
          </td>
          <td class="py-3.5 px-3">
            <div class="font-semibold text-slate-800 dark:text-slate-200 text-sm sm:text-[15px]">${item.doanThanhNien}</div>
            ${(item as any).doanThanhNienPhone ? `<a href="tel:${(item as any).doanThanhNienPhone.replace(/\s+/g, '')}" class="text-xs font-bold text-blue-700 dark:text-blue-400 hover:underline flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-xs">call</span>${(item as any).doanThanhNienPhone}</a>` : ''}
          </td>
        </tr>
      `;
    }).join('');
  }

  private showMeritoriousDetail(id: number) {
    const batch = (this.meritoriousFamilies as any[]).find(f => f.id === id);
    if (!batch) return;

    const modal = document.getElementById('meritorious-modal');
    const badgeEl = document.getElementById('meritorious-modal-badge');
    const titleEl = document.getElementById('meritorious-modal-title');
    const tdpEl = document.getElementById('meritorious-modal-tdp');
    const summaryEl = document.getElementById('meritorious-modal-summary');

    const downloadUrl = batch.file_url || (batch.file_path ? (batch.file_path.startsWith('http') ? batch.file_path : `http://127.0.0.1:8005/api/storage/${batch.file_path}`) : '#');
    const fileName = batch.file_name || 'Danh-sach-chinh-sach.xlsx';

    if (badgeEl) badgeEl.textContent = 'ĐỢT DANH SÁCH CHÍNH SÁCH';
    if (titleEl) titleEl.textContent = batch.name;
    if (tdpEl) tdpEl.innerHTML = `<span class="material-symbols-outlined text-amber-600 text-base">calendar_today</span><span>Thời gian cập nhật: ${batch.created_at || batch.period_date || 'Mới nhất'}</span>`;

    if (summaryEl) {
      summaryEl.innerHTML = `
        <div class="space-y-4">
          <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
            ${batch.description || 'Danh sách tổng hợp chi tiết các hộ gia đình chính sách, người có công với cách mạng trên địa bàn Phường Duy Hà.'}
          </p>
          <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 rounded-2xl border border-emerald-200 dark:border-emerald-900/40 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold shrink-0 shadow-sm">
                <span class="material-symbols-outlined text-xl">table_chart</span>
              </div>
              <div class="text-left">
                <span class="text-xs sm:text-sm font-black text-slate-900 dark:text-white block truncate max-w-[220px] sm:max-w-xs">${fileName}</span>
                <span class="text-[10px] text-emerald-700 dark:text-emerald-400 font-bold block">${batch.file_size || 'Định dạng Excel (.xlsx)'}</span>
              </div>
            </div>
            <a href="${downloadUrl}" download="${fileName}" target="_blank"
              class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl text-xs flex items-center gap-1.5 transition-all shadow-sm shrink-0 active:scale-95">
              <span class="material-symbols-outlined text-sm">download</span>
              <span>Tải file Excel</span>
            </a>
          </div>
        </div>
      `;
    }

    if (modal) {
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }
  }

  private async fetchProceduresData() {
    try {
      const [procRes, videoRes, polRes] = await Promise.all([
        fetch('/api/procedures').catch(() => null),
        fetch('/api/procedure-videos').catch(() => null),
        fetch('/api/policies').catch(() => null)
      ]);

      if (procRes && procRes.ok) {
        const data = await procRes.json();
        if (Array.isArray(data) && data.length > 0) {
          this.proceduresList = data;
        }
      }

      if (videoRes && videoRes.ok) {
        const vData = await videoRes.json();
        if (Array.isArray(vData) && vData.length > 0) {
          this.procedureVideos = vData;
        }
      }

      if (polRes && polRes.ok) {
        const pData = await polRes.json();
        if (Array.isArray(pData) && pData.length > 0) {
          this.policiesList = pData;
        }
      }

      this.renderProceduresSection();
    } catch (e) {
      console.warn('Using local proceduresData fallback');
    }
  }

  private renderProceduresSection() {
    const container = document.getElementById('interactive-procedures-container');
    if (!container) return;

    const popularProceduresFallback = [
      {
        id: 1,
        name: '01. Đăng ký tạm trú',
        title: 'Quy trình đăng ký tạm trú',
        desc: 'Thủ tục đăng ký tạm trú cho công dân cư trú trên địa bàn Phường Duy Hà'
      },
      {
        id: 2,
        name: '02. Thông báo lưu trú',
        title: 'Quy trình thông báo lưu trú',
        desc: 'Thủ tục thông báo lưu trú qua ứng dụng VNeID và Cổng Dịch vụ công'
      },
      {
        id: 3,
        name: '03. Xác nhận cư trú',
        title: 'Quy trình xác nhận thông tin cư trú',
        desc: 'Cấp Giấy xác nhận thông tin về cư trú mẫu CT07 trực tuyến'
      },
      {
        id: 4,
        name: '04. Cài đặt VNeID',
        title: 'Quy trình cài đặt & kích hoạt VNeID',
        desc: 'Hướng dẫn cài đặt, kích hoạt tài khoản định danh điện tử VNeID Mức 2'
      }
    ];

    (window as any).switchProcedureTab = (tab: string) => {
      this.procedureActiveTab = tab;
      this.renderProceduresSection();
    };

    const tabClassActive = 'px-5 py-2.5 bg-[#1d7fe0] text-white rounded-xl shadow-md shrink-0 font-extrabold text-sm sm:text-base transition-all';
    const tabClassInactive = 'px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 shrink-0 font-bold text-sm sm:text-base transition-all';

    let html = `
      <!-- Tabs Sub-navigation -->
      <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-100 dark:border-slate-800 no-scrollbar">
        <button onclick="window.switchProcedureTab('popular')" class="${this.procedureActiveTab === 'popular' ? tabClassActive : tabClassInactive}">Thủ tục hành chính</button>
        <button onclick="window.switchProcedureTab('videos')" class="${this.procedureActiveTab === 'videos' ? tabClassActive : tabClassInactive}">Video hướng dẫn</button>
        <button onclick="window.switchProcedureTab('policies')" class="${this.procedureActiveTab === 'policies' ? tabClassActive : tabClassInactive}">Chính sách</button>
      </div>
    `;

    if (this.procedureActiveTab === 'popular') {
      const activeProcedures = (this.proceduresList && this.proceduresList.length > 0)
        ? this.proceduresList.slice(0, 4)
        : popularProceduresFallback;

      html += `
        <!-- Popular Procedures Tab Content (Full-Width List with Xem chi tiết buttons) -->
        <div class="flex flex-col gap-3.5 pt-2">
          ${activeProcedures.map(item => {
        const itemTitle = item.title || item.name || '';
        const itemDesc = item.desc || item.categoryText || 'Dịch vụ công trực tuyến Phường Duy Hà';

        return `
              <div onclick="window.location.href='/procedures.html'" class="p-4 sm:p-5 bg-white dark:bg-slate-900 hover:bg-sky-50/40 dark:hover:bg-slate-800/80 rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 group cursor-pointer">
                <div class="flex items-center gap-4 flex-1 min-w-0">
                  <div class="space-y-1 min-w-0 flex-1">
                    <h4 class="text-base sm:text-lg font-black text-slate-900 dark:text-white leading-snug group-hover:text-[#1d7fe0] transition-colors">
                      ${itemTitle.replace(/^[0-9]+\.\s*/, '')}
                    </h4>
                    <p class="text-xs sm:text-sm font-semibold text-slate-500 dark:text-slate-400 leading-normal line-clamp-1">
                      ${itemDesc}
                    </p>
                  </div>
                </div>
                <a href="/procedures.html" onclick="event.stopPropagation()"
                  class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-sky-200 dark:border-slate-700 bg-sky-50 dark:bg-slate-800 hover:bg-[#1d7fe0] hover:border-[#1d7fe0] text-[#1d7fe0] dark:text-sky-300 hover:text-white dark:hover:text-white text-xs sm:text-sm font-bold transition-all shadow-sm active:scale-95 shrink-0 whitespace-nowrap group/btn self-end sm:self-center">
                  <span>Xem chi tiết</span>
                  <span class="material-symbols-outlined text-base group-hover/btn:translate-x-0.5 transition-transform">arrow_forward</span>
                </a>
              </div>
            `;
      }).join('')}
        </div>
      `;
    } else if (this.procedureActiveTab === 'videos') {
      const getYoutubeThumbnail = (url: string) => {
        if (!url) return '/hero-bg.jpg';
        const match = url.match(/(?:embed\/|v=|youtu\.be\/|shorts\/)([\w-]{11})/);
        if (match && match[1]) {
          return `https://img.youtube.com/vi/${match[1]}/hqdefault.jpg`;
        }
        return '/hero-bg.jpg';
      };

      const getWatchUrl = (rawUrl: string) => {
        if (!rawUrl) return '/procedures.html?tab=videos';
        let url = rawUrl.trim();
        const ytMatch = url.match(/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|v\/|shorts\/))([\w-]{11})/);
        if (ytMatch && ytMatch[1]) {
          return `https://www.youtube.com/watch?v=${ytMatch[1]}`;
        } else if (url.includes('drive.google.com')) {
          const driveMatch = url.match(/\/file\/d\/([a-zA-Z0-9_-]+)/) || url.match(/[?&]id=([a-zA-Z0-9_-]+)/);
          if (driveMatch && driveMatch[1]) {
            return `https://drive.google.com/file/d/${driveMatch[1]}/view`;
          }
        }
        return url;
      };

      const displayVideos = (this.procedureVideos && this.procedureVideos.length > 0)
        ? this.procedureVideos.slice(0, 4)
        : [];

      html += `
        <!-- Video Guides Tab Content (16:9 Aspect Ratio with Centered Play Button) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
          ${displayVideos.map(item => `
            <a href="${getWatchUrl(item.videoUrl)}" target="_blank" rel="noopener noreferrer"
              class="group block bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/90 dark:border-slate-800 overflow-hidden shadow-sm hover:shadow-xl hover:border-[#1d7fe0] transition-all duration-300 cursor-pointer">
              <!-- Video Thumbnail Container (16:9) -->
              <div class="relative w-full aspect-video bg-slate-950 overflow-hidden">
                <img src="${getYoutubeThumbnail(item.videoUrl)}" alt="${item.title}"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                
                <!-- Dark Overlay Gradient -->
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-black/20 group-hover:opacity-80 transition-opacity pointer-events-none"></div>
                
                <!-- Category Badge (Top-Left) -->
                <div class="absolute top-3 left-3 z-10">
                  <span class="px-2.5 py-1 rounded-lg bg-red-600 text-white text-[10px] sm:text-[11px] font-black uppercase tracking-wider shadow-md backdrop-blur-sm">
                    ${item.categoryText || 'HƯỚNG DẪN'}
                  </span>
                </div>

                <!-- Centered YouTube Play Button -->
                <div class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none">
                  <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-red-600 group-hover:bg-red-700 text-white flex items-center justify-center shadow-2xl group-hover:scale-110 transition-all duration-300 ring-4 ring-white/30 group-hover:ring-white/50">
                    <span class="material-symbols-outlined text-3xl sm:text-4xl translate-x-0.5">play_arrow</span>
                  </div>
                </div>
              </div>

              <!-- Content Below Thumbnail -->
              <div class="p-3.5 sm:p-4 space-y-2">
                <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white group-hover:text-[#1d7fe0] transition-colors line-clamp-2 leading-snug">
                  ${item.title}
                </h4>
                <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 pt-1 border-t border-slate-100 dark:border-slate-800">
                  <span class="font-bold flex items-center gap-1 text-slate-600 dark:text-slate-300">
                    <span class="material-symbols-outlined text-sm text-red-500">smart_display</span>
                    <span>Video HD</span>
                  </span>
                  <span class="text-[#1d7fe0] font-extrabold flex items-center gap-0.5 group-hover:underline">
                    <span>Xem video</span>
                    <span class="material-symbols-outlined text-sm group-hover:translate-x-0.5 transition-transform">open_in_new</span>
                  </span>
                </div>
              </div>
            </a>
          `).join('')}
        </div>
      `;
    } else if (this.procedureActiveTab === 'policies') {
      const displayPolicies = (this.policiesList && this.policiesList.length > 0)
        ? this.policiesList.slice(0, 3)
        : [];

      html += `
        <!-- Policy Documents Tab Content (Real DB Data) -->
        <div class="space-y-3.5 pt-2">
          ${displayPolicies.map(doc => {
            const docLink = doc.downloadUrl && doc.downloadUrl !== '#' ? doc.downloadUrl : '/procedures.html?tab=policies';
            return `
              <div onclick="window.location.href='/procedures.html?tab=policies'"
                class="p-4 sm:p-5 bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-2xl hover:border-[#1d7fe0] hover:shadow-md transition-all cursor-pointer group flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex-1 min-w-0 space-y-1">
                  <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-2 py-0.5 rounded-md bg-sky-50 dark:bg-sky-950 text-[#1d7fe0] dark:text-sky-300 font-black text-[11px] border border-sky-200 dark:border-sky-800">
                      ${doc.code || 'VĂN BẢN'}
                    </span>
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">
                      ${doc.agency || doc.categoryText || 'Chính quyền Phường Duy Hà'}
                    </span>
                  </div>
                  <h4 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white group-hover:text-[#1d7fe0] transition-colors leading-snug">
                    ${doc.title}
                  </h4>
                </div>
                <a href="${docLink}" ${docLink.startsWith('http') || docLink.endsWith('.pdf') ? 'target="_blank" rel="noopener noreferrer"' : ''} onclick="event.stopPropagation()"
                  class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-[#1d7fe0] text-slate-700 dark:text-slate-200 hover:text-white text-xs font-extrabold transition-all shrink-0 self-end sm:self-center">
                  <span class="material-symbols-outlined text-sm">visibility</span>
                  <span>Xem văn bản</span>
                </a>
              </div>
            `;
          }).join('')}
        </div>
      `;
    }

    let targetHref = '/procedures.html?tab=procedures';
    let targetText = 'Xem tất cả thủ tục hành chính →';
    if (this.procedureActiveTab === 'videos') {
      targetHref = '/procedures.html?tab=videos';
      targetText = 'Xem tất cả video hướng dẫn →';
    } else if (this.procedureActiveTab === 'policies') {
      targetHref = '/procedures.html?tab=policies';
      targetText = 'Xem tất cả chính sách & quy định →';
    }

    html += `
      <div class="pt-3">
        <a href="${targetHref}"
          class="w-full py-3.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-extrabold rounded-xl text-xs text-center block transition-all shadow-inner hover:text-[#1d7fe0] dark:hover:text-sky-400">
          ${targetText}
        </a>
      </div>
    `;

    container.innerHTML = html;
  }

  private renderMeritoriousSection() {
    const container = document.getElementById('meritorious-events-container');
    if (!container) return;

    (window as any).selectMeritoriousBatch = (id: number) => {
      this.selectedMeritoriousId = id;
      this.meritoriousSheetIndex = 0;
      this.meritoriousPage = 1;
      this.renderMeritoriousSection();
    };

    (window as any).switchMeritoriousSheet = (sheetIdx: number) => {
      this.meritoriousSheetIndex = sheetIdx;
      this.meritoriousPage = 1;
      this.renderCurrentMeritoriousSheet();
    };

    (window as any).changeMeritoriousPage = (page: number) => {
      this.meritoriousPage = page;
      this.renderCurrentMeritoriousSheet();
    };

    (window as any).changeMeritoriousRowsPerPage = (rows: number) => {
      this.meritoriousRowsPerPage = rows;
      this.meritoriousPage = 1;
      this.renderCurrentMeritoriousSheet();
    };

    const batches = (this.meritoriousFamilies as any[]).filter(f => f.status === 'active' || f.status !== 'inactive');

    if (batches.length === 0) {
      container.innerHTML = `
        <div class="text-center py-16 bg-slate-50 dark:bg-slate-800/40 rounded-3xl border border-slate-200 dark:border-slate-700 space-y-3">
          <div class="w-16 h-16 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center mx-auto text-3xl">
            <span class="material-symbols-outlined text-4xl">folder_off</span>
          </div>
          <p class="text-base font-bold text-slate-700 dark:text-slate-200">Chưa có danh sách gia đình chính sách nào được đăng tải.</p>
          <p class="text-xs text-slate-400">Vui lòng cập nhật danh sách tại trang quản trị Admin.</p>
        </div>
      `;
      return;
    }

    // Default select first batch if none selected or not in list
    if (!this.selectedMeritoriousId || !batches.some(b => b.id === this.selectedMeritoriousId)) {
      this.selectedMeritoriousId = batches[0].id;
    }

    const currentBatch = batches.find(b => b.id === this.selectedMeritoriousId) || batches[0];
    const fileUrl = getLocalFileUrl(currentBatch.file_path || currentBatch.file_url);
    const fileName = currentBatch.file_name || currentBatch.name || 'Danh_sach_chinh_sach.xlsx';
    const isExcel = /\.(xlsx|xls|csv)$/i.test(fileName) || /\.(xlsx|xls|csv)$/i.test(currentBatch.file_path || '');
    const isPdf = /\.pdf$/i.test(fileName) || /\.pdf$/i.test(currentBatch.file_path || '');

    let html = `
      <!-- BATCH SELECTOR (IF MULTIPLE BATCHES) -->
      ${batches.length > 1 ? `
        <div class="flex items-center gap-2 overflow-x-auto pb-2 no-scrollbar border-b border-slate-100 dark:border-slate-800">
          <span class="text-xs font-bold text-slate-400 shrink-0 mr-1 flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">filter_list</span>
            <span>Chọn đợt:</span>
          </span>
          ${batches.map(b => `
            <button onclick="window.selectMeritoriousBatch(${b.id})"
              class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-2 border cursor-pointer ${b.id === this.selectedMeritoriousId ? 'bg-amber-500 text-white border-amber-600 shadow-md scale-102' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:bg-slate-200'}">
              <span class="material-symbols-outlined text-sm">${/\.pdf$/i.test(b.file_name || b.file_path || '') ? 'picture_as_pdf' : 'table_chart'}</span>
              <span>${b.name}</span>
            </button>
          `).join('')}
        </div>
      ` : ''}

      <!-- CURRENT BATCH HEADER & CONTROLS -->
      <div class="bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-transparent dark:from-amber-950/30 dark:via-slate-900 dark:to-slate-900 p-5 sm:p-6 rounded-2xl border border-amber-500/20 dark:border-amber-900/40 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="space-y-1.5 min-w-0 flex-1">
          <div class="flex items-center gap-2 flex-wrap">
            <span class="inline-flex items-center gap-1 text-[11px] font-extrabold ${isExcel ? 'text-emerald-700 dark:text-emerald-400 bg-emerald-100/70 dark:bg-emerald-950/80 border-emerald-300 dark:border-emerald-800' : (isPdf ? 'text-rose-700 dark:text-rose-400 bg-rose-100/70 dark:bg-rose-950/80 border-rose-300 dark:border-rose-800' : 'text-blue-700 dark:text-blue-400 bg-blue-100/70 dark:bg-blue-950/80 border-blue-300 dark:border-blue-800')} px-2.5 py-0.5 rounded-full border">
              <span class="material-symbols-outlined text-xs">${isExcel ? 'table_chart' : (isPdf ? 'picture_as_pdf' : 'draft')}</span>
              <span>${isExcel ? 'TỆP EXCEL (.XLSX)' : (isPdf ? 'TỆP PDF' : 'TÀI LIỆU')}</span>
            </span>
            <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 flex items-center gap-1">
              <span class="material-symbols-outlined text-xs">calendar_today</span>
              <span>${currentBatch.created_at || currentBatch.period_date || 'Mới cập nhật'}</span>
            </span>
            <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 hidden sm:inline-flex items-center gap-1 truncate max-w-xs" title="${fileName}">
              <span class="material-symbols-outlined text-xs">attach_file</span>
              <span class="truncate">${fileName}</span>
            </span>
          </div>
          <h4 class="text-lg sm:text-xl font-black text-slate-900 dark:text-white tracking-tight">
            ${currentBatch.name}
          </h4>
          ${currentBatch.description ? `
            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed font-medium">
              ${currentBatch.description}
            </p>
          ` : ''}
        </div>

        <div class="flex items-center gap-2 shrink-0 self-end md:self-center">
          ${fileUrl ? `
            <a href="${fileUrl}" download="${fileName}" target="_blank"
              class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl transition-all shadow-sm active:scale-95 cursor-pointer"
              title="Tải về máy tính">
              <span class="material-symbols-outlined text-base">download</span>
              <span>Tải về</span>
            </a>
            <a href="${fileUrl}" target="_blank"
              class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-extrabold text-xs rounded-xl transition-all shadow-sm active:scale-95 cursor-pointer"
              title="Mở trong tab mới">
              <span class="material-symbols-outlined text-base">open_in_new</span>
              <span class="hidden sm:inline">Mở tab mới</span>
            </a>
          ` : ''}
        </div>
      </div>

      <!-- LIVE VIEWER CONTAINER -->
      <div id="meritorious-live-viewer-box" class="w-full">
        <!-- Injected by loadAndDisplayMeritoriousFile -->
      </div>
    `;

    container.innerHTML = html;
    this.loadAndDisplayMeritoriousFile(currentBatch);
  }

  private async loadAndDisplayMeritoriousFile(batch: any) {
    const viewerBox = document.getElementById('meritorious-live-viewer-box');
    if (!viewerBox) return;

    const fileUrl = getLocalFileUrl(batch.file_path || batch.file_url);
    const fileName = batch.file_name || batch.name || '';
    const isExcel = /\.(xlsx|xls|csv)$/i.test(fileName) || /\.(xlsx|xls|csv)$/i.test(batch.file_path || '');
    const isPdf = /\.pdf$/i.test(fileName) || /\.pdf$/i.test(batch.file_path || '');

    if (!fileUrl || fileUrl === '#' || fileUrl === '/') {
      viewerBox.innerHTML = `
        <div class="text-center py-12 bg-slate-50 dark:bg-slate-800/40 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-2">
          <span class="material-symbols-outlined text-3xl text-amber-500">warning</span>
          <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Đợt danh sách này chưa có tệp tin đính kèm.</p>
        </div>
      `;
      return;
    }

    if (isPdf) {
      viewerBox.innerHTML = `
        <div class="w-full h-[750px] bg-slate-100 dark:bg-slate-950 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-md">
          <iframe src="${fileUrl}#view=FitH" class="w-full h-full border-0 bg-white" title="Xem danh sách PDF"></iframe>
        </div>
      `;
      return;
    }

    if (isExcel) {
      viewerBox.innerHTML = `
        <div class="flex flex-col items-center justify-center py-16 bg-slate-50 dark:bg-slate-800/40 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-3">
          <div class="animate-spin rounded-full h-8 w-8 border-4 border-emerald-600 border-t-transparent"></div>
          <p class="text-xs font-bold text-slate-600 dark:text-slate-400">Đang đọc và tải dữ liệu bảng tính...</p>
        </div>
      `;

      try {
        let wb = this.meritoriousWorkbookCache.get(fileUrl);
        if (!wb) {
          let res = await fetch(fileUrl).catch(() => null);
          if (!res || !res.ok) {
            const altUrl = batch.file_url || formatStorageUrl(batch.file_path);
            if (altUrl && altUrl !== fileUrl) {
              res = await fetch(altUrl).catch(() => null);
            }
          }
          if (!res || !res.ok) throw new Error('Không thể nạp tệp tin từ máy chủ');
          const arrayBuffer = await res.arrayBuffer();
          wb = XLSX.read(arrayBuffer, { type: 'array' });
          this.meritoriousWorkbookCache.set(fileUrl, wb);
        }

        this.meritoriousParsedWorkbook = wb;
        this.meritoriousSheetIndex = 0;
        this.renderCurrentMeritoriousSheet();
      } catch (err) {
        console.error('Failed to parse Excel spreadsheet:', err);
        viewerBox.innerHTML = `
          <div class="text-center py-12 bg-slate-50 dark:bg-slate-800/40 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-3">
            <span class="material-symbols-outlined text-3xl text-amber-500">error</span>
            <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Không thể mở xem trực tiếp bảng tính này trên trình duyệt.</p>
            <a href="${fileUrl}" download="${fileName}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold shadow-sm">
              <span class="material-symbols-outlined text-base">download</span>
              <span>Bấm vào đây để tải file về máy</span>
            </a>
          </div>
        `;
      }
      return;
    }

    // Default fallback (Images / Other)
    viewerBox.innerHTML = `
      <div class="w-full flex justify-center bg-slate-100 dark:bg-slate-950 p-4 rounded-2xl border border-slate-200 dark:border-slate-800">
        <img src="${fileUrl}" alt="Danh sách chính sách" class="max-w-full max-h-[800px] object-contain rounded-xl shadow" />
      </div>
    `;
  }

  private renderCurrentMeritoriousSheet() {
    const viewerBox = document.getElementById('meritorious-live-viewer-box');
    if (!viewerBox || !this.meritoriousParsedWorkbook) return;

    const wb = this.meritoriousParsedWorkbook;
    const sheetNames = wb.SheetNames || [];
    if (sheetNames.length === 0) {
      viewerBox.innerHTML = `<div class="p-8 text-center text-xs font-bold text-slate-400">Bảng tính không có dữ liệu.</div>`;
      return;
    }

    if (this.meritoriousSheetIndex >= sheetNames.length) {
      this.meritoriousSheetIndex = 0;
    }

    const currentSheetName = sheetNames[this.meritoriousSheetIndex];
    const ws = wb.Sheets[currentSheetName];
    const rawRows: any[][] = XLSX.utils.sheet_to_json(ws, { header: 1, defval: '' });

    // Filter out completely blank rows
    const allRows = rawRows.filter(row => Array.isArray(row) && row.some(cell => String(cell || '').trim() !== ''));

    if (allRows.length === 0) {
      viewerBox.innerHTML = `
        <div class="space-y-4">
          <div class="flex items-center gap-1.5 overflow-x-auto pb-1 no-scrollbar border-b border-slate-100 dark:border-slate-800">
            ${sheetNames.map((sName: string, idx: number) => `
              <button onclick="window.switchMeritoriousSheet(${idx})"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 cursor-pointer border ${idx === this.meritoriousSheetIndex ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:bg-slate-200'}">
                <span class="material-symbols-outlined text-sm">grid_on</span>
                <span>${sName}</span>
              </button>
            `).join('')}
          </div>
          <div class="p-12 text-center text-xs font-bold text-slate-400 bg-slate-50 dark:bg-slate-800/40 rounded-2xl border">
            Sheet "${currentSheetName}" không có dữ liệu hiển thị.
          </div>
        </div>
      `;
      return;
    }

    // Find the header row (first row with at least 2 non-empty cells)
    let headerRowIdx = 0;
    for (let i = 0; i < Math.min(allRows.length, 10); i++) {
      const nonEmpties = allRows[i].filter(c => String(c || '').trim() !== '');
      if (nonEmpties.length >= 2) {
        headerRowIdx = i;
        break;
      }
    }

    const headerRow = allRows[headerRowIdx] || [];
    const dataRows = allRows.slice(headerRowIdx + 1);

    // Determine valid columns that actually contain data or headers
    const rawMaxCols = Math.max(headerRow.length, ...dataRows.map(r => r.length), 1);
    const validColIndices: number[] = [];
    for (let c = 0; c < rawMaxCols; c++) {
      const headerHasVal = headerRow[c] !== undefined && String(headerRow[c] || '').trim() !== '';
      const dataHasVal = dataRows.some(row => row[c] !== undefined && String(row[c] || '').trim() !== '');
      if (headerHasVal || dataHasVal) {
        validColIndices.push(c);
      }
    }
    if (validColIndices.length === 0) validColIndices.push(0);

    // Normalize header row cells
    const normalizedHeaders: string[] = validColIndices.map((col, idx) => {
      return String(headerRow[col] || '').trim() || `Cột ${idx + 1}`;
    });

    // Pagination
    const totalRows = dataRows.length;
    const perPage = this.meritoriousRowsPerPage;
    const totalPages = Math.ceil(totalRows / perPage) || 1;
    if (this.meritoriousPage > totalPages) this.meritoriousPage = totalPages;
    const startIdx = (this.meritoriousPage - 1) * perPage;
    const pageRows = dataRows.slice(startIdx, startIdx + perPage);

    viewerBox.innerHTML = `
      <div class="space-y-4 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-4 sm:p-6 shadow-md">
        <!-- SHEET TABS (IF MULTIPLE SHEETS) -->
        ${sheetNames.length > 1 ? `
          <div class="flex items-center gap-1.5 overflow-x-auto max-w-full pb-3 border-b border-slate-100 dark:border-slate-800 no-scrollbar">
            ${sheetNames.map((sName: string, idx: number) => `
              <button onclick="window.switchMeritoriousSheet(${idx})"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 cursor-pointer border ${idx === this.meritoriousSheetIndex ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:bg-slate-200'}">
                <span class="material-symbols-outlined text-sm">grid_on</span>
                <span>${sName}</span>
              </button>
            `).join('')}
          </div>
        ` : ''}

        <!-- DATA TABLE CONTAINER WITH STICKY HEADER -->
        <div class="overflow-x-auto max-h-[620px] rounded-2xl border border-slate-200 dark:border-slate-700 shadow-inner bg-slate-50/50 dark:bg-slate-950/50">
          <table class="w-full text-left text-xs border-collapse">
            <thead class="sticky top-0 z-10 bg-slate-100 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 shadow-xs">
              <tr>
                <th class="py-2.5 px-3 text-center w-12 font-black text-slate-600 dark:text-slate-300 uppercase tracking-wider border-r border-slate-200 dark:border-slate-700">STT</th>
                ${normalizedHeaders.map((h) => `
                  <th class="py-2.5 px-3 font-extrabold text-slate-700 dark:text-slate-200 whitespace-nowrap border-r border-slate-200/60 dark:border-slate-700/60 last:border-r-0">
                    ${h}
                  </th>
                `).join('')}
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800/70 font-medium text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-900">
              ${pageRows.length === 0 ? `
                <tr>
                  <td colspan="${validColIndices.length + 1}" class="text-center py-12 text-slate-400 font-bold">
                    Không có dữ liệu hiển thị.
                  </td>
                </tr>
              ` : pageRows.map((row, rIdx) => {
                const globalRowIdx = startIdx + rIdx + 1;
                const isTotalRow = row.some(cell => String(cell || '').toLowerCase().includes('tổng cộng'));
                return `
                  <tr class="hover:bg-amber-50/80 dark:hover:bg-slate-800/80 transition-colors ${isTotalRow ? 'bg-amber-100/60 dark:bg-amber-950/40 font-black text-amber-950 dark:text-amber-200' : ''}">
                    <td class="py-2 px-3 text-center text-slate-400 font-bold border-r border-slate-200/60 dark:border-slate-800/60">${globalRowIdx}</td>
                    ${validColIndices.map((origColIdx) => {
                      const val = row[origColIdx] !== undefined && row[origColIdx] !== null ? String(row[origColIdx]) : '';
                      const isNum = val && !isNaN(Number(val.replace(/[,\.]/g, ''))) && val.trim() !== '';
                      return `
                        <td class="py-2 px-3 ${isNum && val.length < 10 ? 'text-right' : ''} border-r border-slate-200/60 dark:border-slate-800/60 last:border-r-0 whitespace-nowrap">
                          ${val || '—'}
                        </td>
                      `;
                    }).join('')}
                  </tr>
                `;
              }).join('')}
            </tbody>
          </table>
        </div>

        <!-- PAGINATION & ROW COUNT FOOTER -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2 text-xs text-slate-500 dark:text-slate-400">
          <div class="font-semibold">
            Hiển thị <span class="font-black text-slate-800 dark:text-slate-200">${totalRows > 0 ? startIdx + 1 : 0} - ${Math.min(startIdx + perPage, totalRows)}</span> trong tổng số <span class="font-black text-slate-800 dark:text-slate-200">${totalRows}</span> dòng
          </div>

          <!-- Pagination buttons -->
          <div class="flex items-center gap-1.5">
            <button onclick="window.changeMeritoriousPage(${this.meritoriousPage - 1})"
              ${this.meritoriousPage <= 1 ? 'disabled class="opacity-40 cursor-not-allowed px-2.5 py-1 rounded-lg border border-slate-200 dark:border-slate-700 font-bold transition-all"' : 'class="hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer px-2.5 py-1 rounded-lg border border-slate-200 dark:border-slate-700 font-bold transition-all"'}
              >
              <span class="material-symbols-outlined text-sm align-middle">chevron_left</span>
              <span>Trước</span>
            </button>

            <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg font-black text-slate-800 dark:text-slate-200">
              ${this.meritoriousPage} / ${totalPages}
            </span>

            <button onclick="window.changeMeritoriousPage(${this.meritoriousPage + 1})"
              ${this.meritoriousPage >= totalPages ? 'disabled class="opacity-40 cursor-not-allowed px-2.5 py-1 rounded-lg border border-slate-200 dark:border-slate-700 font-bold transition-all"' : 'class="hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer px-2.5 py-1 rounded-lg border border-slate-200 dark:border-slate-700 font-bold transition-all"'}
              >
              <span>Sau</span>
              <span class="material-symbols-outlined text-sm align-middle">chevron_right</span>
            </button>
          </div>
        </div>
      </div>
    `;
  }

  private populateOfficialNeighborhoodSelect() {
    const select = document.getElementById('official-neighborhood-select') as HTMLSelectElement;
    if (!select) return;

    select.innerHTML = `<option value="all">Tất cả đơn vị / Khối công tác</option>` +
      this.departments.map(d => `<option value="${d.code}">${d.name}</option>`).join('');
  }

  private filterOfficialsByNeighborhood(deptOrNb: string) {
    this.renderOfficialsGrid(deptOrNb);
  }

  private renderOfficialsGrid(filterDept: string = 'all') {
    const grid = document.getElementById('officials-list-grid');
    if (!grid) return;

    // Lấy tất cả phòng ban đang có trạng thái hoạt động (active)
    const activeDepartments = this.departments.filter(dept => 
      dept.status === 'active' || dept.status !== 'inactive'
    );

    let items = this.officials.filter(o => o.status === 'active' || o.status !== 'inactive');
    if (filterDept !== 'all') {
      const fDept = filterDept.toLowerCase().trim();
      items = items.filter(o => {
        const oDept = (o.department || '').toLowerCase().trim();
        const oNb = Array.isArray(o.neighborhood_name) 
          ? o.neighborhood_name.map(n => String(n).toLowerCase().trim()).join(' ')
          : String(o.neighborhood_name || '').toLowerCase().trim();
        return oDept === fDept || oNb.includes(fDept);
      });
    }

    const renderCard = (o: Official) => {
      return `
        <div class="bg-white dark:bg-slate-900/90 p-6 sm:p-7 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-md hover:shadow-xl flex flex-col items-center text-center transition-all hover:scale-[1.02] duration-300 justify-between">
          <div class="flex flex-col items-center text-center w-full">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-slate-800 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-3.5 shrink-0 border border-blue-100 dark:border-slate-700 shadow-inner">
              <span class="material-symbols-outlined text-3xl">badge</span>
            </div>
            <h5 class="text-lg sm:text-xl font-black text-slate-900 dark:text-slate-100 leading-snug">${o.name}</h5>
            <span class="text-sm sm:text-[15px] font-bold text-slate-600 dark:text-slate-300 mt-1.5 leading-snug min-h-[32px] flex items-center justify-center">${o.role}</span>
          </div>
          <a href="tel:${o.phone}" class="mt-5 inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-50 dark:bg-blue-950/50 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 dark:hover:text-white text-blue-600 dark:text-blue-400 rounded-2xl font-black text-sm sm:text-base transition-all w-full border border-blue-200 dark:border-blue-900/50 shadow-sm active:scale-95">
            <span class="material-symbols-outlined text-lg">call</span>
            <span>${o.phone}</span>
          </a>
        </div>
      `;
    };

    const getDeptStyle = (color?: string, code?: string) => {
      if (code === 'dang_uy' || color === 'danger' || color === 'red') {
        return { text: 'text-red-600 dark:text-red-400', dot: 'bg-red-600' };
      }
      if (code === 'cong_an' || color === 'warning' || color === 'amber') {
        return { text: 'text-amber-600 dark:text-amber-400', dot: 'bg-amber-600' };
      }
      if (code === 'chinh_quyen' || color === 'success' || color === 'emerald') {
        return { text: 'text-emerald-600 dark:text-emerald-400', dot: 'bg-emerald-600' };
      }
      if (code === 'ttpvhcc' || color === 'info' || color === 'sky') {
        return { text: 'text-blue-600 dark:text-blue-400', dot: 'bg-blue-600' };
      }
      return { text: 'text-indigo-600 dark:text-indigo-400', dot: 'bg-indigo-600' };
    };

    let html = '';

    // Render theo thứ tự từng đơn vị được phép hiển thị
    activeDepartments.forEach((dept, index) => {
      const dCode = (dept.code || '').toLowerCase().trim();
      const dName = (dept.name || '').toLowerCase().trim();

      const deptOfficials = items.filter(o => {
        const oDept = (o.department || '').toLowerCase().trim();
        if (oDept && (oDept === dCode || oDept === dName)) return true;
        
        if (Array.isArray(o.neighborhood_name)) {
          return o.neighborhood_name.some((n: string) => {
            const nStr = String(n).toLowerCase().trim();
            return nStr.includes(dName) || (dCode && nStr.includes(dCode));
          });
        } else if (typeof o.neighborhood_name === 'string') {
          const nStr = o.neighborhood_name.toLowerCase().trim();
          return nStr.includes(dName) || (dCode && nStr.includes(dCode));
        }
        return false;
      });

      if (deptOfficials.length === 0) return;

      const style = getDeptStyle(dept.color, dept.code);

      html += `
        <div class="col-span-full ${index > 0 ? 'mt-8' : 'mb-2'}">
          <h4 class="text-base sm:text-lg font-black uppercase tracking-wide ${style.text} border-b-2 border-slate-100 dark:border-slate-800 pb-3 mb-5 flex items-center gap-2.5">
            <span class="w-3 h-3 rounded-full ${style.dot} ring-4 ring-slate-100 dark:ring-slate-800"></span>
            <span>${dept.name.toUpperCase()}</span>
            <span class="text-xs sm:text-sm font-extrabold px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 ml-auto lowercase shadow-sm">
              ${deptOfficials.length} cán bộ
            </span>
          </h4>
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            ${deptOfficials.map(renderCard).join('')}
          </div>
        </div>
      `;
    });

    if (html.trim() === '') {
      html = `
        <div class="col-span-full py-12 text-center text-slate-400 dark:text-slate-500 font-semibold">
          <span class="material-symbols-outlined text-4xl mb-2 text-slate-300">folder_open</span>
          <p>Chưa có thông tin cán bộ cho các phòng ban đang hoạt động.</p>
        </div>
      `;
    }

    grid.innerHTML = html;
  }

  private viewPlaceDetail(id: number) {
    const place = this.places.find(p => p.id === id);
    if (!place) return;

    this.currentPlace = place;

    const modal = document.getElementById('portal-detail-modal');
    const name = document.getElementById('modal-place-name');
    const desc = document.getElementById('modal-description');
    const addr = document.getElementById('modal-address');
    const directionsBtn = document.getElementById('modal-directions-btn') as HTMLAnchorElement | null;
    const statsRow = document.getElementById('modal-stats-row');

    if (name) name.textContent = place.name;
    if (desc) desc.textContent = place.description || 'Chưa có thông tin giới thiệu chi tiết.';
    if (addr) addr.textContent = place.address || 'Phường Duy Hà, tỉnh Ninh Bình';

    if (directionsBtn) {
      if (place.lat && place.lng) {
        directionsBtn.href = `https://www.google.com/maps/dir/?api=1&destination=${place.lat},${place.lng}`;
      } else {
        directionsBtn.href = `https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(place.name + ' Phường Duy Hà')}`;
      }
    }

    if (statsRow) {
      if (place.households || place.population || place.hours || place.cultural_house_address) {
        statsRow.innerHTML = `
          ${place.households ? `<div class="bg-blue-50 dark:bg-slate-800 p-3 rounded-2xl text-center border border-blue-100 dark:border-slate-700/60"><b class="text-base sm:text-lg font-bold text-blue-700 dark:text-blue-400 block">${place.households}</b><span class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Số hộ dân</span></div>` : ''}
          ${place.population ? `<div class="bg-emerald-50 dark:bg-slate-800 p-3 rounded-2xl text-center border border-emerald-100 dark:border-slate-700/60"><b class="text-base sm:text-lg font-bold text-emerald-700 dark:text-emerald-400 block">${place.population}</b><span class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Dân số</span></div>` : ''}
          ${place.hours ? `<div class="bg-amber-50 dark:bg-slate-800 p-3 rounded-2xl text-center border border-amber-100 dark:border-slate-700/60"><b class="text-xs sm:text-sm font-bold text-amber-700 dark:text-amber-400 block mt-0.5">${place.hours}</b><span class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Giờ làm việc</span></div>` : ''}
          ${place.cultural_house_address ? `<div class="bg-purple-50 dark:bg-slate-800 p-3 rounded-2xl text-center border border-purple-100 dark:border-slate-700/60"><b class="text-xs font-bold text-purple-700 dark:text-purple-400 block truncate mt-0.5">${place.cultural_house_address}</b><span class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Nhà văn hóa</span></div>` : ''}
        `;
        statsRow.classList.remove('hidden');
      } else {
        statsRow.classList.add('hidden');
      }
    }

    if (modal) {
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }
  }

  private closePortalModal() {
    const modal = document.getElementById('portal-detail-modal');
    if (modal) {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }
  }

  private toggleMapView() {
    const mapContainer = document.getElementById('map-view-container');
    if (!mapContainer) return;

    const isHidden = mapContainer.classList.contains('hidden');
    if (isHidden) {
      this.showPortalTab('map');
    } else {
      this.showPortalTab('home');
    }
  }

  private initLeafletMap() {
    const mapEl = document.getElementById('map');
    if (!mapEl) return;

    this.map = L.map('map', {
      zoomControl: true,
      attributionControl: false
    }).setView([20.6478448, 105.914737], 14.5);

    L.tileLayer('https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
      maxZoom: 20,
      attribution: ''
    }).addTo(this.map);

    // Render boundary of Phường Duy Hà (GeoJSON)
    // @ts-ignore
    const boundaryLayer = L.geoJSON(DUY_HA_BOUNDARY, {
      style: {
        color: '#1D4ED8',
        weight: 3.5,
        dashArray: '6, 6',
        fillColor: '#3B82F6',
        fillOpacity: 0.12
      }
    }).addTo(this.map);

    // Focus & restrict camera bounds to Phường Duy Hà boundary
    const bounds = boundaryLayer.getBounds();
    if (bounds.isValid()) {
      this.map.fitBounds(bounds, { padding: [40, 40] });
      this.map.setMaxBounds(bounds.pad(0.35));
      this.map.setMinZoom(13);
    }

    // Render Places with Custom Category Icon Markers (Exclude 'neighborhood' / Tổ dân phố)
    const mapPlaces = this.places.filter(p => p.category !== 'neighborhood');
    mapPlaces.forEach(p => {
      let iconName = 'location_on';
      let gradient = 'from-blue-600 to-blue-800';
      let borderColor = 'border-blue-300';

      if (p.category === 'government') {
        iconName = 'account_balance';
        gradient = 'from-blue-600 to-indigo-800';
        borderColor = 'border-blue-300';
      } else if (p.category === 'police') {
        iconName = 'local_police';
        gradient = 'from-indigo-600 to-purple-900';
        borderColor = 'border-indigo-300';
      } else if (p.category === 'health') {
        iconName = 'local_hospital';
        gradient = 'from-red-600 to-rose-800';
        borderColor = 'border-red-300';
      } else if (p.category === 'school') {
        iconName = 'school';
        gradient = 'from-amber-500 to-orange-700';
        borderColor = 'border-amber-300';
      } else if (p.category === 'neighborhood') {
        iconName = 'holiday_village';
        gradient = 'from-emerald-600 to-teal-800';
        borderColor = 'border-emerald-300';
      }

      const customIcon = L.divIcon({
        className: 'custom-leaflet-marker',
        html: `
          <div class="relative group cursor-pointer flex flex-col items-center">
            <div class="w-9 h-9 rounded-full bg-gradient-to-tr ${gradient} text-white shadow-xl border-2 ${borderColor} flex items-center justify-center transition-transform hover:scale-125">
              <span class="material-symbols-outlined text-lg">${iconName}</span>
            </div>
            <div class="mt-1 px-2.5 py-1 rounded-md bg-slate-900/90 backdrop-blur-md text-white font-bold text-xs shadow-md whitespace-nowrap border border-slate-700 pointer-events-none">
              ${p.name}
            </div>
          </div>
        `,
        iconSize: [140, 56],
        iconAnchor: [70, 20],
        popupAnchor: [0, -22]
      });

      const marker = L.marker([p.lat, p.lng], { icon: customIcon });
      marker.bindPopup(`
        <div style="font-family:'Inter',sans-serif; padding:8px; min-width:210px;">
          <div style="font-size:12px; font-weight:800; text-transform:uppercase; color:#1D4ED8; margin-bottom:4px;">
            ${p.category === 'government' ? 'Cơ quan Hành chính' : p.category === 'police' ? 'Công an Phường' : p.category === 'health' ? 'Cơ sở Y tế' : p.category === 'school' ? 'Trường học' : 'Tổ dân phố'}
          </div>
          <b style="color:#0F172A; font-size:16px; display:block; margin-bottom:4px;">${p.name}</b>
          <span style="font-size:14px; color:#64748B; display:block; margin-bottom:10px;">${p.address || ''}</span>
          <a href="https://www.google.com/maps/dir/?api=1&destination=${p.lat},${p.lng}" target="_blank" rel="noopener noreferrer" style="width:100%; background:#1D4ED8; color:white; border:none; padding:9px 12px; border-radius:8px; font-size:14px; font-weight:bold; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:6px; text-decoration:none; box-sizing:border-box;">
            <span class="material-symbols-outlined" style="font-size:18px;">near_me</span>
            <span>Chỉ đường</span>
          </a>
        </div>
      `);
      marker.addTo(this.map!);
    });

    // Render places carousel at bottom of map
    this.renderMapPlacesCarousel();
  }

  private initEventListeners() {

    const modal = document.getElementById('portal-detail-modal');
    if (modal) {
      modal.addEventListener('click', (e) => {
        if (e.target === modal) {
          this.closePortalModal();
        }
      });
    }

    const tdpModal = document.getElementById('tdp-modal');
    if (tdpModal) {
      tdpModal.addEventListener('click', (e) => {
        if (e.target === tdpModal) {
          this.closeTdpModal();
        }
      });
    }

    const mapSearchInput = document.getElementById('map-search-input') as HTMLInputElement;
    if (mapSearchInput) {
      mapSearchInput.addEventListener('input', () => {
        this.renderMapPlacesCarousel(mapSearchInput.value);
      });
    }

    setTimeout(() => this.map?.invalidateSize(), 250);
  }
}

/**
 * Initialize fixed floating Back-to-Top button at bottom-right corner
 */
export function initBackToTopButton(): void {
  let btn = document.getElementById('back-to-top-btn');
  if (!btn) {
    btn = document.createElement('button');
    btn.id = 'back-to-top-btn';
    btn.setAttribute('title', 'Lên đầu trang');
    btn.className = 'fixed bottom-6 right-6 z-50 w-12 h-12 rounded-2xl bg-[#1d7fe0] hover:bg-[#1565c0] text-white flex items-center justify-center shadow-2xl hover:shadow-sky-500/40 transition-all duration-300 active:scale-95 border border-white/30 cursor-pointer opacity-0 pointer-events-none';
    btn.innerHTML = '<span class="material-symbols-outlined text-2xl font-black">arrow_upward</span>';
    btn.onclick = () => window.scrollTo({ top: 0, behavior: 'smooth' });
    document.body.appendChild(btn);
  }

  const toggleVisibility = () => {
    if (window.scrollY > 200) {
      btn?.classList.remove('opacity-0', 'pointer-events-none');
      btn?.classList.add('opacity-100');
    } else {
      btn?.classList.add('opacity-0', 'pointer-events-none');
      btn?.classList.remove('opacity-100');
    }
  };

  window.addEventListener('scroll', toggleVisibility);
  toggleVisibility();
}

/**
 * Animate numeric values with count-up effect on page load
 */
export function animateCountUp(element: HTMLElement, duration: number = 1500): void {
  const originalText = element.innerText.trim();
  if (!originalText || element.dataset.animated === 'true') return;
  element.dataset.animated = 'true';

  // Extract number pattern from string (e.g., "6.767", "23.615", "15,46 km²", "10")
  const match = originalText.match(/([0-9.,]+)(.*)/);
  if (!match) return;

  const rawNumStr = match[1];
  const suffix = match[2] || '';

  const hasCommaDecimal = rawNumStr.includes(',') && !rawNumStr.includes('.');
  const isDotThousands = rawNumStr.includes('.') && !rawNumStr.includes(',');

  let targetNum = 0;
  let decimals = 0;

  if (hasCommaDecimal) {
    const parts = rawNumStr.split(',');
    decimals = parts[1] ? parts[1].length : 0;
    targetNum = parseFloat(rawNumStr.replace(',', '.'));
  } else if (isDotThousands) {
    targetNum = parseInt(rawNumStr.replace(/\./g, ''), 10);
  } else {
    targetNum = parseFloat(rawNumStr);
  }

  if (isNaN(targetNum) || targetNum <= 0) return;

  const startTime = performance.now();

  const updateCount = (currentTime: number) => {
    const elapsedTime = currentTime - startTime;
    const progress = Math.min(elapsedTime / duration, 1);

    // Ease-out cubic formula for smooth slowdown
    const easeProgress = 1 - Math.pow(1 - progress, 3);
    const currentNum = targetNum * easeProgress;

    let formattedStr = '';
    if (hasCommaDecimal) {
      formattedStr = currentNum.toFixed(decimals).replace('.', ',');
    } else if (isDotThousands) {
      formattedStr = Math.round(currentNum).toLocaleString('vi-VN');
    } else {
      formattedStr = Math.round(currentNum).toString();
    }

    element.innerText = formattedStr + suffix;

    if (progress < 1) {
      requestAnimationFrame(updateCount);
    } else {
      element.innerText = originalText;
    }
  };

  requestAnimationFrame(updateCount);
}

export function triggerStatCardsCountUp(): void {
  const container = document.getElementById('stats-cards-container');
  if (!container) return;

  const statElements = container.querySelectorAll('b');
  if (statElements.length === 0) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        statElements.forEach(el => animateCountUp(el as HTMLElement, 1500));
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  observer.observe(container);
}

document.addEventListener('DOMContentLoaded', () => {
  // Inject shared header/nav into all pages from single source of truth
  initSharedHeader();
  initSharedFooter();
  initBackToTopButton();
  initSubpageBanners();
  new PortalApp();
  triggerStatCardsCountUp();
});
