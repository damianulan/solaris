/// <reference types="vite/client" />

import '@inertiajs/core';

declare module '@inertiajs/core' {
    interface PageProps {
        theme: {
            colors: Record<string, string>;
        };
    }
}
