import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import jquery from "jquery";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            $: "jquery",
        },
    },
    build: {
        rollupOptions: {
            external: ["summernote/dist/summernote-lite.js"],
        },
    },
});
