import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
    build: {
        chunkSizeWarningLimit: 1000,
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        if (id.includes('apexcharts')) {
                            return 'vendor-apexcharts';
                        }
                        if (id.includes('flatpickr')) {
                            return 'vendor-flatpickr';
                        }
                        if (id.includes('alpinejs')) {
                            return 'vendor-alpine';
                        }
                        return 'vendor';
                    }
                },
            },
        },
    },
});
