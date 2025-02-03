import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ command }) => ({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    base: command === 'serve' ? '' : process.env.VITE_BASE_URL || '/build/',
    build: {
        manifest: true,
        outDir: 'public/build',
        rollupOptions: {
            output: {
                assetFileNames: 'assets/[ext]/[name]-[hash][extname]',
                chunkFileNames: 'js/[name]-[hash].js',
                entryFileNames: 'js/[name]-[hash].js',
            },
        },
    },
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
    server: {
        https: process.env.HTTPS === 'true',
        host: '0.0.0.0',
        hmr: {
            host: process.env.VITE_HMR_HOST || 'localhost'
        },
    },
}));
