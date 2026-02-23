import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import type { DefineComponent } from 'vue'; // For type safety

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => title ? `${title} - ${appName}` : appName,
    resolve: (name) => {
        const pages = import.meta.glob<DefineComponent>('./Pages/**/*.vue', { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            // You can add other plugins here (e.g., ZiggyVue for routing)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
