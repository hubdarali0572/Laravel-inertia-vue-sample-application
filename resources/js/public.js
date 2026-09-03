import "../css/public/public-theme.css";
import "../css/public/public.css";
import "../css/public/public-components.css";
import "../css/public/public-utilities.css";
import "../css/public/public-responsive.css";
import "./bootstrap";

import { createInertiaApp, Link, Head } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h, nextTick } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import { router } from "@inertiajs/vue3";
import AOS from "aos";
import "aos/dist/aos.css";

AOS.init({
    duration: 550,
    once: true,
    offset: 48,
    easing: "ease-out",
});

function lockPublicDocument() {
    document.documentElement.lang = "en";
    document.documentElement.dir = "ltr";
    document.documentElement.classList.remove("dark", "rtl");
}

router.on("success", () => {
    lockPublicDocument();
    nextTick(() => {
        AOS.refresh();
    });
});

const appName = "Bright Future Educational Institute";

createInertiaApp({
    title: (title) => (title ? `${title} | ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/PublicSite/**/*.vue"),
        ),
    setup({ el, App, props, plugin }) {
        lockPublicDocument();

        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .component("Link", Link)
            .component("Head", Head)
            .mount(el);
    },
    progress: {
        color: "#0f766e",
    },
});
