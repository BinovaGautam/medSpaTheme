import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin'
import { wordpressPlugin, wordpressThemeJson } from '@roots/vite-plugin';

export default defineConfig({
  base: '/app/themes/preetidreams-medical-spa-theme/public/build/',
  plugins: [
    tailwindcss(),
    laravel({
      input: [
        'resources/styles/app.scss',
        'resources/scripts/app.ts',
        'resources/styles/editor.scss',
        'resources/scripts/editor.ts',
      ],
      refresh: [
        'app/**/*.php',
        'resources/**/*.{php,js,ts,scss,css}',
        'patterns/**/*.php',
        'theme.json',
      ],
    }),

    wordpressPlugin({
      wordPressUrl: process.env.WP_HOME || 'http://localhost:8080',
      themeRoot: process.env.WP_THEME_ROOT || '/app/themes/preetidreams-medical-spa-theme',
    }),

    wordpressThemeJson({
      disableTailwindColors: false,
      disableTailwindFonts: false,
      disableTailwindFontSizes: false,
      includeGlobalStylesInEditor: true,
      generateStyleVariations: true,
    }),
  ],
  resolve: {
    alias: {
      '@scripts': '/resources/scripts',
      '@styles': '/resources/styles',
      '@fonts': '/resources/fonts',
      '@images': '/resources/images',
      '@components': '/resources/scripts/components',
      '@utils': '/resources/scripts/utils',
      '@services': '/resources/scripts/services',
      '@types': '/resources/scripts/types',
    },
  },
  build: {
    rollupOptions: {
      output: {
        manualChunks: {
          vendor: ['axe-core'],
          'medical-forms': [
            '/resources/scripts/components/ConsultationForm.ts',
            '/resources/scripts/components/BookingWidget.ts',
          ],
          'gallery': [
            '/resources/scripts/components/BeforeAfterGallery.ts',
            '/resources/scripts/components/TreatmentGrid.ts',
          ],
          'accessibility': [
            '/resources/scripts/utils/accessibility.ts',
            '/resources/scripts/utils/hipaa-helpers.ts',
          ],
        },
      },
    },
    cssCodeSplit: true,
    sourcemap: process.env.NODE_ENV === 'development',
    minify: 'esbuild',
    chunkSizeWarningLimit: 250,
    assetsInlineLimit: 4096,
  },
  server: {
    hmr: {
      host: 'localhost',
    },
    watch: {
      usePolling: true,
      interval: 100,
    },
  },
  css: {
    preprocessorOptions: {
      scss: {
        // Remove additionalData to avoid import conflicts
      },
    },
    devSourcemap: true,
  },
  define: {
    __MEDICAL_SPA_VERSION__: JSON.stringify(process.env.npm_package_version || '1.0.0'),
    __HIPAA_MODE__: JSON.stringify(process.env.HIPAA_MODE === 'true'),
    __ACCESSIBILITY_MODE__: JSON.stringify(process.env.ACCESSIBILITY_MODE !== 'false'),
  },
  optimizeDeps: {
    include: [
      'axe-core',
    ],
    exclude: [
    ],
  },
})
