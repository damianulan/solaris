import '../css/app.scss';
import '@fontsource/ibm-plex-sans/400.css';
import '@fontsource/ibm-plex-sans/500.css';
import '@fontsource/ibm-plex-sans/600.css';
import '@fontsource/ibm-plex-sans/700.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import 'vuetify/styles';

import { createInertiaApp } from '@inertiajs/vue3';
import { createApp, h, type DefineComponent } from 'vue';

import { createVuetifyPlugin } from './plugins/vuetify';

const pages = import.meta.glob<{ default: DefineComponent }>('./Pages/**/*.vue');

createInertiaApp({
    title: (title) => (title ? `${title} - ${import.meta.env.VITE_APP_NAME ?? 'Solaris'}` : 'Solaris'),
    resolve: (name) => {
        const page = pages[`./Pages/${name}.vue`];

        if (!page) {
            throw new Error(`Inertia page "${name}" was not found.`);
        }

        return page().then((module) => module.default);
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(createVuetifyPlugin(props.initialPage.props.theme.colors))
            .mount(el);
    },
});
