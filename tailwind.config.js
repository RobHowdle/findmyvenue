import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Raleway", "sans-serif"],
                heading: ["Roboto", "sans-serif"],
            },
            colors: {
                opacBlack: "rgba(255,255,255,0.4)",
                ynsRed: "#EF3F38",
                ynsPurple: "#9022BB",
                ynsCyan: "#29C0D2",
                ynsTeal: "#53E5D6",
                ynsYellow: "#D59220",
                ynsDarkOrange: "#F03F37",
            },
        },
        screens: {
            sm: "320px",
            // => @media (min-width: 640px) { ... }

            md: "575px",
            // => @media (min-width: 768px) { ... }

            lg: "757px",
            // => @media (min-width: 1024px) { ... }

            xl: "991px",
            // => @media (min-width: 1280px) { ... }

            "2xl": "1199px",
            // => @media (min-width: 1536px) { ... }

            "3xl": "1366px",
            // => @media (min-width: 1536px) { ... }

            "4xl": "1680px",
            // => @media (min-width: 1536px) { ... }
        },
        maxHeight: {
            "40rem": "40rem",
        },
    },

    plugins: [forms, require("daisyui")],
};
