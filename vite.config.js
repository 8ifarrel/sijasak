import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 5173,
        https: false,
        hmr: {
            host: 'https://raised-cnet-creativity-oklahoma.trycloudflare.com',
            protocol: 'wss',
        },
    },
    plugins: [
        tailwindcss(),
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
});
