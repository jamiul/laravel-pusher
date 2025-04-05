import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: true,  // Helps with file watching in Docker
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // Pass env variables to frontend
    define: {
        'import.meta.env.VITE_PUSHER_APP_KEY': JSON.stringify(process.env.PUSHER_APP_KEY),
        'import.meta.env.VITE_PUSHER_APP_CLUSTER': JSON.stringify(process.env.PUSHER_APP_CLUSTER),
    },
});
