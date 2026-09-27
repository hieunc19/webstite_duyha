type TrackedFetch = typeof window.fetch;

interface SubpageLoaderState {
  installed: boolean;
  coreReady: boolean;
  pendingRequests: number;
  settleTimer: number | null;
  fallbackTimer: number | null;
  originalFetch: TrackedFetch | null;
}

const state: SubpageLoaderState = {
  installed: false,
  coreReady: false,
  pendingRequests: 0,
  settleTimer: null,
  fallbackTimer: null,
  originalFetch: null,
};

function isHomepage(): boolean {
  return Boolean(document.getElementById('portal-main-view'));
}

function shouldTrackRequest(input: RequestInfo | URL): boolean {
  try {
    const rawUrl = input instanceof Request ? input.url : String(input);
    const url = new URL(rawUrl, window.location.origin);
    if (url.origin !== window.location.origin) return false;

    return url.pathname.startsWith('/api/')
      || url.pathname.startsWith('/data/')
      || url.pathname.startsWith('/src/data/');
  } catch {
    return false;
  }
}

function finishLoading(): void {
  if (!state.installed) return;

  if (state.fallbackTimer !== null) {
    window.clearTimeout(state.fallbackTimer);
    state.fallbackTimer = null;
  }

  document.body.classList.remove('subpage-loading');
  document.body.classList.add('subpage-ready', 'js-hydrated');

  const loader = document.getElementById('subpage-db-loader');
  if (loader) {
    loader.setAttribute('aria-hidden', 'true');
    window.setTimeout(() => loader.remove(), 180);
  }
}

function scheduleFinish(): void {
  if (!state.coreReady || state.pendingRequests > 0) return;

  if (state.settleTimer !== null) {
    window.clearTimeout(state.settleTimer);
  }

  // Give synchronous render callbacks one frame to finish before revealing.
  state.settleTimer = window.setTimeout(() => {
    state.settleTimer = null;
    if (state.coreReady && state.pendingRequests === 0) {
      finishLoading();
    }
  }, 120);
}

function createLoader(): void {
  if (document.getElementById('subpage-db-loader')) return;

  const loader = document.createElement('div');
  loader.id = 'subpage-db-loader';
  loader.setAttribute('role', 'status');
  loader.setAttribute('aria-live', 'polite');
  loader.innerHTML = `
    <div class="subpage-loader-card">
      <div class="subpage-loader-spinner" aria-hidden="true"></div>
      <div class="subpage-loader-title">Đang tải dữ liệu từ máy chủ...</div>
      <div class="subpage-loader-detail">Vui lòng chờ trong giây lát.</div>
    </div>
  `;
  document.body.prepend(loader);
}

export function installSubpageLoader(): void {
  if (state.installed || isHomepage()) return;

  state.installed = true;
  document.body.classList.remove('subpage-ready');
  document.body.classList.add('subpage-loading');
  createLoader();

  state.originalFetch = window.fetch.bind(window);
  window.fetch = (async (...args: Parameters<TrackedFetch>) => {
    const tracked = shouldTrackRequest(args[0]);

    if (tracked) {
      state.pendingRequests += 1;
      if (state.settleTimer !== null) {
        window.clearTimeout(state.settleTimer);
        state.settleTimer = null;
      }
    }

    try {
      return await state.originalFetch!(...args);
    } finally {
      if (tracked) {
        state.pendingRequests = Math.max(0, state.pendingRequests - 1);
        scheduleFinish();
      }
    }
  }) as TrackedFetch;

  // Never leave the visitor behind an infinite loader if a third-party or API
  // request behaves unexpectedly. Existing fallback content can still render.
  state.fallbackTimer = window.setTimeout(finishLoading, 12000);
}

export function markSubpageDataReady(): void {
  if (!state.installed) return;
  state.coreReady = true;
  scheduleFinish();
}
