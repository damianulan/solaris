<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import type { DataTableHeader } from 'vuetify';

import { useTranslations } from '../../lib/translations';
import type { DataTableActionButton, DataTableBlueprint, DataTableColumn } from '../../lib/types';
import IconButton from '../buttons/IconButton.vue';
import ConfirmationDialog from '../dialogs/ConfirmationDialog.vue';

interface DataTableOptions {
    page: number;
    itemsPerPage: number;
    sortBy: DataTableSortItem[];
}

interface DataTableSortItem {
    key: string;
    order: 'asc' | 'desc';
}

const props = defineProps<{
    table: DataTableBlueprint;
}>();

defineSlots<{
    actions?: (props: { selectedItems: Array<number | string> }) => unknown;
}>();

const { translate } = useTranslations();
const page = usePage();
const isLoading = ref(false);
const pendingAction = ref<DataTableActionButton | null>(null);
const selectedItems = ref<Array<number | string>>([]);
const isConfirmationDialogOpen = computed({
    get: () => pendingAction.value !== null,
    set: (isOpen: boolean) => {
        if (!isOpen) {
            pendingAction.value = null;
        }
    },
});
const selectableColumn = computed(() => props.table.columns.find((column) => column.type === 'selectable'));
const actionColumns = computed(() => props.table.columns.filter((column) => column.type === 'action'));
const dataColumns = computed(() => props.table.columns.filter((column) => column.type === 'default'));
const headers = computed<DataTableHeader[]>(() => props.table.columns.map((column) => column.type === 'selectable'
    ? {
        align: resolveAlignment(column.alignment),
        key: 'data-table-select',
        sortable: false,
        title: column.label ?? '',
    }
    : {
        align: resolveAlignment(column.alignment),
        key: column.name,
        sortable: column.sortable,
        title: column.label ?? '',
        value: column.name,
    }));
const itemsPerPageOptions = computed(() => props.table.pagination.map((value) => value === -1
    ? { title: translate('frontend.data_table.all_items'), value }
    : value));
const sortBy = ref<DataTableSortItem[]>(initialSort());

function resolveAlignment(alignment: DataTableColumn['alignment']): DataTableHeader['align'] {
    if (alignment === 'right') {
        return 'end';
    }

    if (alignment === 'center') {
        return 'center';
    }

    return 'start';
}

function initialSort(): DataTableSortItem[] {
    const parameters = queryParameters();
    const key = parameters.get('sortBy');
    const column = props.table.columns.find((candidate) => candidate.name === key);

    if (!column?.sortable) {
        return [];
    }

    return [{
        key: column.name,
        order: parameters.get('sortDirection')?.toLowerCase() === 'desc' ? 'desc' : 'asc',
    }];
}

function queryParameters(): URLSearchParams {
    return new URLSearchParams(page.url.split('?')[1] ?? '');
}

function actionButtons(value: unknown): DataTableActionButton[] {
    if (!Array.isArray(value)) {
        return [];
    }

    return value.filter((button): button is DataTableActionButton => (
        typeof button === 'object'
        && button !== null
        && typeof Reflect.get(button, 'href') === 'string'
    ));
}

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

    if (!action) {
        return;
    }

    visitAction(action);
}

function visitAction(button: DataTableActionButton): void {
    router.visit(button.href, {
        method: button.method ?? 'get',
        preserveScroll: button.method !== undefined && button.method !== 'get',
    });
}

function loadTable(options: DataTableOptions): void {
    const paginator = props.table.paginator;
    const parameters = queryParameters();
    const activeSort = options.sortBy[0];
    const currentSortBy = parameters.get('sortBy');
    const currentSortDirection = parameters.get('sortDirection')?.toLowerCase() ?? null;

    if (
        options.page === paginator.current_page
        && options.itemsPerPage === paginator.per_page
        && (activeSort?.key ?? null) === currentSortBy
        && (activeSort?.order ?? null) === currentSortDirection
    ) {
        return;
    }

    const url = new URL(window.location.href);
    const sortingChanged = (activeSort?.key ?? null) !== currentSortBy
        || (activeSort?.order ?? null) !== currentSortDirection;

    url.searchParams.set('page', String(sortingChanged ? 1 : options.page));
    url.searchParams.set('perPage', String(options.itemsPerPage));

    if (activeSort) {
        url.searchParams.set('sortBy', activeSort.key);
        url.searchParams.set('sortDirection', activeSort.order);
    } else {
        url.searchParams.delete('sortBy');
        url.searchParams.delete('sortDirection');
    }

    router.visit(url, {
        only: ['table'],
        preserveScroll: true,
        preserveState: true,
        replace: true,
        onStart: () => {
            isLoading.value = true;
        },
        onFinish: () => {
            isLoading.value = false;
        },
    });
}
</script>

<template>
    <v-card border flat>
        <div v-if="$slots.actions" class="data-table-actions d-flex flex-wrap justify-end ga-2 pa-4">
            <slot name="actions" :selected-items="selectedItems" />
        </div>

        <v-data-table-server
            class="data-table"
            :headers="headers"
            :items="table.paginator.data"
            :items-length="table.paginator.total"
            :items-per-page="table.paginator.per_page"
            :items-per-page-options="itemsPerPageOptions"
            :items-per-page-text="translate('frontend.data_table.items_per_page')"
            :page-text="translate('frontend.data_table.page_text')"
            :first-page-label="translate('frontend.data_table.first_page')"
            :prev-page-label="translate('frontend.data_table.previous_page')"
            :next-page-label="translate('frontend.data_table.next_page')"
            :last-page-label="translate('frontend.data_table.last_page')"
            :loading-text="translate('frontend.data_table.loading')"
            :no-data-text="translate('frontend.data_table.no_data')"
            :loading="isLoading"
            :page="table.paginator.current_page"
            :select-all-label="translate('frontend.data_table.select_all')"
            :select-row-label="translate('frontend.data_table.select_row')"
            :show-select="selectableColumn !== undefined"
            v-model="selectedItems"
            v-model:sort-by="sortBy"
            item-value="id"
            must-sort
            select-strategy="page"
            fixed-header
            show-first-last-page
            gridlines="horizontal"
            @update:options="loadTable"
        >
            <template
                v-for="column in dataColumns"
                :key="column.name"
                #[`item.${column.name}`]="{ value }"
            >
                {{ value === null ? translate('frontend.data_table.empty_value') : value }}
            </template>

            <template
                v-for="column in actionColumns"
                :key="column.name"
                #[`item.${column.name}`]="{ value }"
            >
                <div class="d-flex justify-end ga-1">
                    <IconButton
                        v-for="(button, index) in actionButtons(value)"
                        :key="`${column.name}-${index}`"
                        :aria-label="button.title"
                        :color="button.color"
                        :disabled="button.disabled"
                        :icon="button.icon"
                        size="small"
                        :title="button.title"
                        :variant="button.variant"
                        @click="performAction(button)"
                    />
                </div>
            </template>
        </v-data-table-server>

        <ConfirmationDialog
            v-model="isConfirmationDialogOpen"
            :cancel-label="translate('frontend.data_table.confirmation.cancel')"
            :color="pendingAction?.color"
            :confirm-label="pendingAction?.title ?? translate('frontend.data_table.confirmation.confirm')"
            :message="pendingAction?.confirm ?? ''"
            :title="translate('frontend.data_table.confirmation.title')"
            @confirm="confirmAction"
        />
    </v-card>
</template>

<style scoped>
.data-table :deep(.v-table__wrapper > table > thead > tr > th) {
    background: color-mix(in srgb, rgb(var(--v-theme-surface)) 90%, black);
    border: 0;
    border-top: 2px solid rgba(var(--v-border-color), calc(var(--v-border-opacity) * 2));
    border-bottom: 2px solid rgba(var(--v-border-color), calc(var(--v-border-opacity) * 2));
    box-shadow: none;
}

.data-table :deep(.v-table__wrapper > table > tbody > tr:hover > td) {
    background: color-mix(in srgb, rgb(var(--v-theme-surface)) 96%, black);
}
</style>
