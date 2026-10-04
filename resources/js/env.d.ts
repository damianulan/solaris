/// <reference types="vite/client" />

import '@inertiajs/core';
import type { Localization, NavigationElement, Page, Snackbar, Theme } from './lib/types';

declare module '@inertiajs/core' {
    interface PageProps {
        localization: Localization;
        sidebar: NavigationElement[];
        theme: Theme;
        page: Page;
        snackbars: Snackbar[];
    }
}
