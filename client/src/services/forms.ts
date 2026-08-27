// Administrative Forms (Thủ tục hành chính) Master Data & Controller

export interface FormItem {
  id: number;
  code: string;
  name: string;
  category: string;
  category_name: string;
  category_badge: string;
  agency: string;
  fee: string;
  purpose: string;
  steps: string[];
  docs: string[];
  notes: string;
  downloads: { docx: boolean; pdf: boolean };
}
import formDocumentsSeed from '../data/form_documents.json';

export const FORMS_DATA: FormItem[] = (formDocumentsSeed as any[]).map(item => ({
  ...item,
  name: item.title || item.name,
  category_name: item.category_name || item.category_text || 'Thủ tục hành chính',
  category_badge: item.category_badge || 'bg-sky-100 text-sky-800 dark:bg-sky-950 dark:text-sky-300',
  downloads: item.downloads || { docx: true, pdf: true }
}));

let currentCategory = 'all';
let currentSearchQuery = '';
let selectedFormId: number | null = null;
let MASTER_FORMS_DATA: any[] = [...FORMS_DATA];

function getFormFileUrl(item: any): string | null {
  if (!item) return null;
  let url = item.download_url || item.downloadUrl || item.file_path || item.file_url || item.file || null;
  if (!url || url === '#') return null;
  url = String(url).trim();
  if (!url) return null;
  if (url.startsWith('http://') || url.startsWith('https://')) return url;
  if (url.startsWith('/storage/')) return url;
  if (url.startsWith('/')) return url;
  return `/storage/${url.replace(/^\/+/, '')}`;
}

function showToast(message: string, type: 'success' | 'warning' = 'success') {
  const toast = document.createElement('div');
  const bgClass = type === 'success' ? 'bg-emerald-600' : 'bg-amber-600';
  const icon = type === 'success' ? 'download_done' : 'warning';
  toast.className = `fixed bottom-6 right-6 z-[4000] ${bgClass} text-white px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-3 text-xs sm:text-sm font-bold animate-bounce`;
  toast.innerHTML = `
    <span class="material-symbols-outlined text-xl">${icon}</span>
    <span>${message}</span>
  `;
  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 4000);
}

export async function fetchFormDocuments() {
  try {
    let res = await fetch('/api/form-documents');
    if (!res.ok) {
      res = await fetch('/src/data/form_documents.json');
    }
    if (res.ok) {
      const data = await res.json();
      if (Array.isArray(data) && data.length > 0) {
        MASTER_FORMS_DATA = data.map((item: any) => ({
          ...item,
          name: item.title || item.name,
          category_name: item.category_name || item.category_text || 'Thủ tục hành chính',
          category_badge: item.category_badge || 'bg-sky-100 text-sky-800 dark:bg-sky-950 dark:text-sky-300',
        }));
        renderFormsTable();
      }
    }
  } catch (e) {
    console.warn('Could not load form documents:', e);
  }
}

export function renderFormsTable() {
  const tbody = document.getElementById('forms-table-body');
  const badge = document.getElementById('forms-count-badge');
  if (!tbody) return;

  const dataset = MASTER_FORMS_DATA.length > 0 ? MASTER_FORMS_DATA : FORMS_DATA;
  let filtered = dataset.filter(item => {
    const matchCat = currentCategory === 'all' || item.category === currentCategory;
    const q = currentSearchQuery.toLowerCase();
    const matchSearch = !q || (item.name && item.name.toLowerCase().includes(q)) || (item.code && item.code.toLowerCase().includes(q)) || (item.agency && item.agency.toLowerCase().includes(q));
    return matchCat && matchSearch;
  });

  if (badge) badge.textContent = `${filtered.length} biểu mẫu`;

  if (filtered.length === 0) {
    tbody.innerHTML = `
      <tr>
        <td colspan="6" class="py-12 text-center text-slate-400">
          <span class="material-symbols-outlined text-4xl block mb-2">find_in_page</span>
          <p class="font-bold text-sm">Không tìm thấy biểu mẫu phù hợp với từ khóa "${currentSearchQuery}"</p>
        </td>
      </tr>
    `;
    return;
  }

  tbody.innerHTML = filtered.map((item, index) => {
    return `
      <tr class="hover:bg-sky-50/50 dark:hover:bg-slate-800/50 transition-colors">
        <td class="py-4 px-4 border-r border-slate-200 dark:border-slate-800 text-center font-bold text-slate-500">${index + 1}</td>
        <td class="py-4 px-4 border-r border-slate-200 dark:border-slate-800 text-center">
          <span class="inline-block px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 rounded-lg text-xs font-black border border-slate-300/80 dark:border-slate-700 font-mono">
            ${item.code || 'FORM'}
          </span>
        </td>
        <td class="py-4 px-5 border-r border-slate-200 dark:border-slate-800">
          <a href="javascript:void(0)" onclick="window.previewFormFile(${item.id})" class="text-slate-900 dark:text-white font-extrabold hover:text-[#3399fe] transition-colors leading-snug block">
            ${item.name}
          </a>
        </td>
        <td class="py-4 px-4 border-r border-slate-200 dark:border-slate-800 text-center">
          <span class="inline-block px-3 py-1 rounded-full text-xs font-bold ${item.category_badge}">
            ${item.category_name}
          </span>
        </td>
        <td class="py-4 px-4 border-r border-slate-200 dark:border-slate-800 text-center text-xs font-semibold text-slate-600 dark:text-slate-300">
          ${item.agency || 'Bộ phận Một cửa'}
        </td>
        <td class="py-4 px-4 text-center">
          <div class="flex items-center justify-center gap-2.5">
            <button onclick="window.previewFormFile(${item.id})"
              class="px-4 py-2 bg-sky-50/80 dark:bg-sky-950/40 hover:bg-sky-100 text-[#1d7fe0] dark:text-sky-400 text-xs sm:text-sm font-bold rounded-full border border-sky-300 dark:border-sky-700 transition-all flex items-center justify-center active:scale-95 whitespace-nowrap shadow-xs"
              title="Xem trước file đính kèm">
              <span>Hướng dẫn</span>
            </button>
            <button onclick="window.downloadFormFile(${item.id})"
              class="px-4 py-2 bg-[#059669] hover:bg-[#047857] text-white text-xs sm:text-sm font-bold rounded-full shadow-sm transition-all flex items-center justify-center gap-1.5 active:scale-95 whitespace-nowrap"
              title="Tải biểu mẫu đính kèm">
              <span class="material-symbols-outlined text-base">download</span>
              <span>Tải file đính kèm</span>
            </button>
          </div>
        </td>
      </tr>
    `;
  }).join('');
}

export function filterByCategory(category: string) {
  currentCategory = category;
  const optItems = document.querySelectorAll('#form-category-menu .form-opt-item');
  optItems.forEach(item => {
    const val = item.getAttribute('data-value') || 'all';
    if (val === category) {
      item.className = "form-opt-item active w-full flex items-center justify-start px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold text-[#1d7fe0] dark:text-sky-400 bg-sky-50 dark:bg-sky-950/50 transition-all text-left cursor-pointer";
    } else {
      item.className = "form-opt-item w-full flex items-center justify-start px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-[#1d7fe0] dark:hover:text-sky-400 transition-all text-left cursor-pointer";
    }
  });
  renderFormsTable();
}

export function previewFormFile(id: number) {
  const dataset = MASTER_FORMS_DATA.length > 0 ? MASTER_FORMS_DATA : FORMS_DATA;
  const item = dataset.find((f: any) => f.id === id);
  if (!item) return;

  const fileUrl = getFormFileUrl(item);
  if (!fileUrl) {
    showToast('Biểu mẫu này chưa được Quản trị viên đính kèm file hướng dẫn.', 'warning');
    return;
  }

  window.open(fileUrl, '_blank');
}

export function downloadFormFile(id: number) {
  const dataset = MASTER_FORMS_DATA.length > 0 ? MASTER_FORMS_DATA : FORMS_DATA;
  const item = dataset.find((f: any) => f.id === id);
  if (!item) return;

  const fileUrl = getFormFileUrl(item);
  if (!fileUrl) {
    showToast('Biểu mẫu này chưa được Quản trị viên đính kèm file để tải về.', 'warning');
    return;
  }

  const link = document.createElement('a');
  link.href = fileUrl;
  link.setAttribute('download', '');
  link.target = '_blank';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);

  showToast(`Đang tải về: ${item.name || item.title}`, 'success');
}

export function openFormGuideModal(id: number) {
  previewFormFile(id);
}

export function closeFormGuideModal() {
  const modal = document.getElementById('form-guide-modal');
  if (modal) {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }
}

export function triggerDirectDownload(id: number, _format?: string) {
  downloadFormFile(id);
}

export function downloadCurrentForm(_format?: string) {
  if (selectedFormId) {
    downloadFormFile(selectedFormId);
  }
}

export function initFormsView() {
  renderFormsTable();

  const searchInput = document.getElementById('form-search-input');
  if (searchInput) {
    searchInput.addEventListener('input', (e: any) => {
      currentSearchQuery = e.target.value.trim();
      renderFormsTable();
    });
  }

  const catBtn = document.getElementById('form-category-btn');
  const catMenu = document.getElementById('form-category-menu');
  const catLabel = document.getElementById('form-category-selected-label');
  const catArrow = document.getElementById('form-category-arrow');
  const optItems = document.querySelectorAll('#form-category-menu .form-opt-item');

  if (catBtn && catMenu) {
    catBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isHidden = catMenu.classList.contains('hidden');
      if (isHidden) {
        catMenu.classList.remove('hidden');
        if (catArrow) catArrow.classList.add('rotate-180');
      } else {
        catMenu.classList.add('hidden');
        if (catArrow) catArrow.classList.remove('rotate-180');
      }
    });

    document.addEventListener('click', (e) => {
      if (!catMenu.contains(e.target as Node) && !catBtn.contains(e.target as Node)) {
        catMenu.classList.add('hidden');
        if (catArrow) catArrow.classList.remove('rotate-180');
      }
    });

    optItems.forEach(item => {
      item.addEventListener('click', () => {
        const val = item.getAttribute('data-value') || 'all';
        const text = item.querySelector('span:first-child')?.textContent || '';

        currentCategory = val;
        if (catLabel) catLabel.textContent = text;

        optItems.forEach(i => {
          i.className = 'form-opt-item w-full flex items-center justify-start px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-[#1d7fe0] dark:hover:text-sky-400 transition-all text-left cursor-pointer';
        });

        item.className = 'form-opt-item active w-full flex items-center justify-start px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold text-[#1d7fe0] dark:text-sky-400 bg-sky-50 dark:bg-sky-950/50 transition-all text-left cursor-pointer';

        catMenu.classList.add('hidden');
        if (catArrow) catArrow.classList.remove('rotate-180');

        renderFormsTable();
      });
    });
  }

  (window as any).filterByCategory = filterByCategory;
  (window as any).openFormGuideModal = openFormGuideModal;
  (window as any).closeFormGuideModal = closeFormGuideModal;
  (window as any).triggerDirectDownload = triggerDirectDownload;
  (window as any).downloadCurrentForm = downloadCurrentForm;
}
