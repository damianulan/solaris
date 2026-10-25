/// <reference types="vite/client" />

import '@inertiajs/core';
import type { Alert, AuthContext, Banner, Localization, NavigationElement, Page, Snackbar, Theme } from './lib/types';

declare module '@inertiajs/core' {
    interface PageProps {
        auth: AuthContext;
        availableLocales: string[];
        localization: Localization;
        sidebar: NavigationElement[];
        theme: Theme;
        page: Page;
        alerts: Alert[];
        snackbars: Snackbar[];
        banner?: Banner | string | null;
    }
}
