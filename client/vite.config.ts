import { defineConfig } from 'vite';
import { resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const __dirname = fileURLToPath(new URL('.', import.meta.url));

export default defineConfig({
  cacheDir: './node_modules/.vite_app',
  plugins: [
    {
      name: 'redirect-admin',
      configureServer(server) {
        server.middlewares.use((req, res, next) => {
          if (req.url === '/admin' || req.url === '/admin/' || req.url === '/admin.html') {
            res.statusCode = 302;
            res.setHeader('Location', 'http://127.0.0.1:8005/admin');
            res.end();
            return;
          }
          next();
        });
      }
    }
  ],
  server: {
    proxy: {
      '/api': {
        target: 'http://127.0.0.1:8005',
        changeOrigin: true,
      },
      '/storage': {
        target: 'http://127.0.0.1:8005',
        changeOrigin: true,
      }
    }
  },
  optimizeDeps: {
    include: ['leaflet', 'leaflet.markercluster', 'swiper', 'leaflet-routing-machine'],
  },
  build: {
    rollupOptions: {
      input: {
        main: resolve(__dirname, './index.html'),
        tdp_merger: resolve(__dirname, './tdp-merger.html'),
        meritorious_families: resolve(__dirname, './meritorious-families.html'),
        officials: resolve(__dirname, './officials.html'),
        waste_schedule: resolve(__dirname, './waste-schedule.html'),
        agencies: resolve(__dirname, './agencies.html'),
        procedures: resolve(__dirname, './procedures.html'),
        video_guides: resolve(__dirname, './video-guides.html'),
        policies: resolve(__dirname, './policies.html'),
        feedback: resolve(__dirname, './feedback.html'),
        citizen_reception: resolve(__dirname, './citizen-reception.html')
      }
    }
  }
});
