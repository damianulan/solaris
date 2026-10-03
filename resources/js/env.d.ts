/// <reference types="vite/client" />

import '@inertiajs/core';
import type { Localization, Theme } from './lib/types';

declare module '@inertiajs/core' {
    interface PageProps {
        localization: Localization;
        theme: Theme;
    }
}
