/// <reference types="vite/client" />

import '@inertiajs/core';
import type { Theme } from './lib/types';

declare module '@inertiajs/core' {
    interface PageProps {
        theme: Theme;
    }
}
