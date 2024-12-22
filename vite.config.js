import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    resolve: {
        modules: ["node_modules"],
        alias: {
            $: "jquery",
            jquery: "jquery",
        },
    },
    build: {
        rollupOptions: {
            external: ["jquery", "summernote/dist/summernote-lite.js"],
        },
    },
});
