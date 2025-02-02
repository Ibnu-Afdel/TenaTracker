// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';

// export default defineConfig({
//     plugins: [
//         laravel({
//             input: ['resources/css/app.css', 'resources/js/app.js'],
//             refresh: true,
//         }),
//     ],
// });


// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';

// export default defineConfig({
//     plugins: [
//         laravel({
//             input: ['resources/css/app.css', 'resources/js/app.js'],
//             refresh: true,
//         }),
//     ],
//     server: {
//         hmr: {
//             host: 'localhost',
//         },
//     },
// });



// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';

// export default defineConfig({
//     plugins: [
//         laravel({
//             input: ['resources/css/app.css', 'resources/js/app.js'],
//             refresh: true,
//         }),
//     ],
//     build: {
//         manifest: true,
//         outDir: 'public/build',
//     },
//     server: {
//         strictPort: true,
//         hmr: {
//             host: 'tenatracker-production.up.railway.app',
//             protocol: 'wss', // WebSocket Secure
//         },
//         cors: {
//             origin: '*', // Allow all origins
//             credentials: true, // Allow credentials if needed
//         }
//     },
// });


import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        strictPort: true,
        hmr: process.env.APP_ENV === 'local' ? { host: 'localhost' } : false, // Prevents mixed content in production
    },
    build: {
        outDir: 'public/build', // Ensure the correct build folder
        manifest: true,
        rollupOptions: {
            input: {
                app: 'resources/js/app.js',
            },
        },
    },
});
