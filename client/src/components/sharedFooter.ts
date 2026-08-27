import homepageSectionsData from '../data/homepage_sections.json';

/**
 * Shared Footer Component
 * Standard 12-column footer for all pages in the portal
 * Fully synchronized with Admin Page Builder (footer_section)
 */

interface FooterConfig {
  footerLogo: string;
  orgName: string;
  address: string;
  workingHours: string;
  email: string;
  phone: string;
  facebookUrl: string;
  websiteUrl: string;
  copyrightText: string;
  sourceNote: string;
}

let activeFooterConfig: FooterConfig = {
  footerLogo: '/logo.jpg',
  orgName: 'ĐOÀN TNCS HỒ CHÍ MINH PHƯỜNG DUY HÀ',
  address: 'Số 01 đường Lê Lợi, Phường Duy Hà, thành phố Ninh Bình, tỉnh Ninh Bình',
  workingHours: 'Sáng: 7h30 - 11h30 | Chiều: 13h30 - 17h00 (Từ Thứ 2 đến Thứ 6, nghỉ T7 & CN)',
  email: 'thongtin@duyha.ninhbinh.gov.vn',
  phone: '(0229) 38253536',
  facebookUrl: 'https://facebook.com',
  websiteUrl: 'https://duyha.ninhbinh.gov.vn',
  copyrightText: 'Copyright © Đoàn TNCS Hồ Chí Minh phường Duy Hà. All Rights Reserved',
  sourceNote: 'Ghi rõ nguồn "Đoàn TNCS Hồ Chí Minh phường Duy Hà" khi phát hành lại thông tin từ Đoàn TNCS Hồ Chí Minh phường Duy Hà.',
};

// Initialize default config from static JSON if available
try {
  const footerSec = (homepageSectionsData as any[]).find(s => s.section_code === 'footer_section');
  if (footerSec) {
    const s = footerSec.settings || {};
    activeFooterConfig = {
      footerLogo: s.footer_logo || '/logo.jpg',
      orgName: s.org_name || footerSec.custom_title || activeFooterConfig.orgName,
      address: s.address || footerSec.custom_subtitle || activeFooterConfig.address,
      workingHours: s.working_hours || activeFooterConfig.workingHours,
      email: s.email || activeFooterConfig.email,
      phone: s.phone || activeFooterConfig.phone,
      facebookUrl: s.facebook_url || activeFooterConfig.facebookUrl,
      websiteUrl: s.website_url || activeFooterConfig.websiteUrl,
      copyrightText: s.copyright_text || activeFooterConfig.copyrightText,
      sourceNote: s.source_note || activeFooterConfig.sourceNote,
    };
  }
} catch (e) {
  // Use defaults
}

export function applySharedFooterConfig(sectionData: any): void {
  if (!sectionData) return;
  const s = sectionData.settings || {};
  activeFooterConfig = {
    footerLogo: s.footer_logo || '/logo.jpg',
    orgName: s.org_name || sectionData.custom_title || activeFooterConfig.orgName,
    address: s.address || sectionData.custom_subtitle || activeFooterConfig.address,
    workingHours: s.working_hours || activeFooterConfig.workingHours,
    email: s.email || activeFooterConfig.email,
    phone: s.phone || activeFooterConfig.phone,
    facebookUrl: s.facebook_url || activeFooterConfig.facebookUrl,
    websiteUrl: s.website_url || activeFooterConfig.websiteUrl,
    copyrightText: s.copyright_text || activeFooterConfig.copyrightText,
    sourceNote: s.source_note || activeFooterConfig.sourceNote,
  };

  const footerEl = document.querySelector('footer');
  if (footerEl) {
    footerEl.outerHTML = renderFooter();
  }
}

export function renderFooter(): string {
  const cfg = activeFooterConfig;
  const logoSrc = cfg.footerLogo.startsWith('http') || cfg.footerLogo.startsWith('/storage/')
    ? cfg.footerLogo
    : (cfg.footerLogo.startsWith('/') ? cfg.footerLogo : `/storage/${cfg.footerLogo}`);

  return `
  <footer class="bg-gradient-to-r from-[#143e78] via-[#1a4a8c] to-[#123668] text-white pt-8 pb-6 text-sm relative border-t-4 border-amber-500 shadow-2xl overflow-hidden mt-12">
    <div class="absolute inset-0 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:24px_24px] opacity-5 pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10 space-y-6">

      <!-- Top Row: Grid 12 cols -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">

        <!-- Left 7 Cols: Logo & Org Details -->
        <div class="lg:col-span-7 flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-4 sm:gap-5">
          <div class="w-14 h-14 sm:w-20 sm:h-20 shrink-0 flex items-center justify-center">
            <img src="${logoSrc}" alt="Logo ${cfg.orgName}" class="w-full h-full object-contain filter drop-shadow-md" onerror="this.onerror=null; this.src='/logo.jpg';" />
          </div>

          <!-- Text Info -->
          <div class="space-y-1.5 flex flex-col items-center sm:items-start">
            <h3 class="text-base sm:text-lg font-black tracking-wide uppercase text-white drop-shadow-sm">
              ${cfg.orgName}
            </h3>
            <p class="text-xs sm:text-sm text-sky-100 font-medium">
              Địa chỉ: ${cfg.address}
            </p>
            <p class="text-xs sm:text-sm text-sky-100 font-medium">
              <strong>Giờ làm việc:</strong> ${cfg.workingHours}
            </p>
            <p class="text-xs sm:text-sm text-sky-100 font-medium">
              Email: ${cfg.email} &nbsp;|&nbsp; Điện thoại: ${cfg.phone}
            </p>

            <!-- Social & Web Link Icons (Small Circular Badges) -->
            <div class="flex items-center gap-2 pt-1.5 justify-center sm:justify-start">
              <span class="text-xs font-semibold text-sky-200/90 mr-1">Kênh liên kết:</span>

              <!-- Facebook Icon (Small Circle) -->
              <a href="${cfg.facebookUrl}" target="_blank" rel="noopener noreferrer"
                class="w-7 h-7 rounded-full bg-white/15 hover:bg-[#1877F2] text-white flex items-center justify-center transition-all duration-300 shadow-sm border border-white/30 hover:scale-110 hover:border-transparent"
                title="Trang Facebook Phường Duy Hà">
                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                  <path
                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                </svg>
              </a>

              <!-- Website Icon (Small Circle) -->
              <a href="${cfg.websiteUrl}" target="_blank" rel="noopener noreferrer"
                class="w-7 h-7 rounded-full bg-white/15 hover:bg-emerald-600 text-white flex items-center justify-center transition-all duration-300 shadow-sm border border-white/30 hover:scale-110 hover:border-transparent"
                title="Cổng thông tin điện tử Phường Duy Hà">
                <span class="material-symbols-outlined text-[17px]">language</span>
              </a>

              <!-- Portal Mail Icon (Small Circle) -->
              <a href="mailto:${cfg.email}"
                class="w-7 h-7 rounded-full bg-white/15 hover:bg-amber-600 text-white flex items-center justify-center transition-all duration-300 shadow-sm border border-white/30 hover:scale-110 hover:border-transparent"
                title="Hộp thư điện tử">
                <span class="material-symbols-outlined text-[17px]">mail</span>
              </a>
            </div>
          </div>
        </div>

        <!-- Right 5 Cols: Liên kết website -->
        <div class="lg:col-span-5 space-y-2">
          <h4 class="text-sm font-extrabold text-white tracking-wide">Liên kết website</h4>
          <div class="space-y-2">
            <select onchange="if(this.value) window.open(this.value, '_blank')"
              class="w-full bg-white/15 hover:bg-white/25 text-white border border-white/30 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm font-semibold outline-none focus:border-amber-400 transition-colors cursor-pointer appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23FFFFFF%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:10px_10px] bg-[right_12px_center] bg-no-repeat pr-8">
              <option value="" class="bg-slate-900 text-white">Các cơ quan, đơn vị</option>
              <option value="https://ninhbinh.gov.vn" class="bg-slate-900 text-white">UBND Tỉnh Ninh Bình</option>
              <option value="https://congan.ninhbinh.gov.vn" class="bg-slate-900 text-white">Công an Tỉnh Ninh Bình</option>
              <option value="https://stttt.ninhbinh.gov.vn" class="bg-slate-900 text-white">Sở Thông tin &amp; Truyền thông</option>
            </select>

            <select onchange="if(this.value) window.open(this.value, '_blank')"
              class="w-full bg-white/15 hover:bg-white/25 text-white border border-white/30 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm font-semibold outline-none focus:border-amber-400 transition-colors cursor-pointer appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23FFFFFF%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E')] bg-[length:10px_10px] bg-[right_12px_center] bg-no-repeat pr-8">
              <option value="" class="bg-slate-900 text-white">Chọn đơn vị liên kết</option>
              <option value="https://dichvucong.gov.vn" class="bg-slate-900 text-white">Cổng Dịch vụ công Quốc gia</option>
              <option value="https://chinhphu.vn" class="bg-slate-900 text-white">Cổng Thông tin Điện tử Chính phủ</option>
              <option value="https://bocongan.gov.vn" class="bg-slate-900 text-white">Cổng Thông tin Điện tử Bộ Công an</option>
            </select>
          </div>
        </div>

      </div>

      <!-- Divider line -->
      <div class="border-t border-white/15"></div>

      <!-- Bottom Bar: Copyright Only -->
      <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-xs sm:text-sm text-sky-100/90 text-center sm:text-left">
        <p class="font-medium">${cfg.copyrightText}</p>
        <p class="text-xs text-sky-200/80">${cfg.sourceNote}</p>
      </div>

    </div>
  </footer>`;
}

export function initSharedFooter(): void {
  const footerEl = document.querySelector('footer');
  if (footerEl) {
    footerEl.outerHTML = renderFooter();
  }
}
