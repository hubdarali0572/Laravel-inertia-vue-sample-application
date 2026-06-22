import '../css/app.css';
import './bootstrap';

import { createInertiaApp, Link, Head } from '@inertiajs/vue3'; // 1. Import Link and Head
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Sample Application';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .component('Link', Link) // 2. Register Link globally
            .component('Head', Head) // 3. Register Head globally
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});