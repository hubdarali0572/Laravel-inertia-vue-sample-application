import "../css/app.css";
import "./bootstrap";

import { createInertiaApp, Link, Head } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h, nextTick } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";

import { router } from "@inertiajs/vue3";
import AOS from "aos";
import "aos/dist/aos.css";

AOS.init();

function applyDocumentLocale(props) {
    const locale = props?.locale || "en";
    const dir = props?.dir || "ltr";
    document.documentElement.lang = locale;
    document.documentElement.dir = dir;
    document.documentElement.classList.toggle("rtl", dir === "rtl");
}

router.on("success", (event) => {
    applyDocumentLocale(event.detail.page.props);
    nextTick(() => {
        AOS.refresh();
    });
});

const appName = import.meta.env.VITE_APP_NAME || "Sample Application";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue"),
        ),
    setup({ el, App, props, plugin }) {
        applyDocumentLocale(props.initialPage?.props ?? props);

        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .component("Link", Link) // 2. Register Link globally
            .component("Head", Head) // 3. Register Head globally
            .mount(el);
    },
    progress: {
        color: "#C7F8FE",
    },
});
