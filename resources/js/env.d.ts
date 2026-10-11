/// <reference types="vite/client" />

import '@inertiajs/core';
import type { Alert, Localization, NavigationElement, Page, Snackbar, Theme } from './lib/types';

declare module '@inertiajs/core' {
    interface PageProps {
        localization: Localization;
        sidebar: NavigationElement[];
        theme: Theme;
        page: Page;
        alerts: Alert[];
        snackbars: Snackbar[];
    }
}
