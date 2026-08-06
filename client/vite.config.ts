import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
  build: {
    rollupOptions: {
      input: {
        main: resolve(__dirname, './index.html'),
        admin: resolve(__dirname, './admin.html'),
        tdp_merger: resolve(__dirname, './tdp-merger.html')
      }
    }
  }
});
