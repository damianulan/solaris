import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import type { DataTableActionButton } from '../../lib/types';

export function useDataTableActions() {
    const pendingAction = ref<DataTableActionButton | null>(null);
    const isConfirmationDialogOpen = computed({
        get: () => pendingAction.value !== null,
        set: (isOpen: boolean) => {
            if (!isOpen) {
                pendingAction.value = null;
            }
        },
    });

    function performAction(button: DataTableActionButton): void {
        if (button.disabled) {
            return;
        }

        if (button.confirm) {
            pendingAction.value = button;

            return;
        }

        visitAction(button);
    }

    function confirmAction(): void {
        const action = pendingAction.value;

        pendingAction.value = null;

        if (action) {
            visitAction(action);
        }
    }

    function visitAction(button: DataTableActionButton): void {
        router.visit(button.href, {
            method: button.method ?? 'get',
            preserveScroll: button.method !== undefined && button.method !== 'get',
        });
    }

    return {
        confirmAction,
        isConfirmationDialogOpen,
        pendingAction,
        performAction,
    };
}
