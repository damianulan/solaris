import '../css/app.scss';
import '@fontsource/jost/400.css';
import '@fontsource/jost/500.css';
import '@fontsource/jost/600.css';
import '@fontsource/jost/700.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import 'vuetify/styles';

import { createInertiaApp } from '@inertiajs/vue3';
import { createApp, createSSRApp, h, type DefineComponent } from 'vue';

import AppLoadingOverlay from './components/layout/feedback/AppLoadingOverlay.vue';
import { translateMessage } from './lib/translations';
import createVuetifyPlugin from './plugins/vuetify';

const pages = import.meta.glob<{ default: DefineComponent }>('./Pages/**/*.vue');

createInertiaApp({
    progress: false,
    title: (title, page) => {
        const appName = translateMessage(page.props.localization.messages, 'frontend.app.name');

        return title ? `${title} - ${appName}` : appName;
    },
    resolve: (name) => {
        const page = pages[`./Pages/${name}.vue`];

        if (!page) {
            throw new Error(`Page "${name}" was not found.`);
        }

        return page().then((module) => module.default);
    },
    setup({ el, App, props, plugin }) {
        const vueApp = (el === null || el.hasAttribute('data-server-rendered') ? createSSRApp : createApp)({
            render: () => [h(App, props), h(AppLoadingOverlay)],
        });

        vueApp.use(plugin).use(createVuetifyPlugin(props.initialPage.props.theme));

        if (el) {
            vueApp.mount(el);
        }

        return vueApp;
    },
});
