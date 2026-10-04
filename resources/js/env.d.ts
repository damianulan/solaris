/// <reference types="vite/client" />

import '@inertiajs/core';
import type { Localization, NavigationElement, Theme, Page } from './lib/types';

declare module '@inertiajs/core' {
    interface PageProps {
        localization: Localization;
        sidebar: NavigationElement[];
        theme: Theme;
        page: Page;
    }
}
