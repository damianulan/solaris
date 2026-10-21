<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import type { DataTableHeader } from 'vuetify';

import { useTranslations } from '../../lib/translations';
import type { DataTableBlueprint, DataTableColumn } from '../../lib/types';

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

const { translate } = useTranslations();
const page = usePage();
const isLoading = ref(false);
const headers = computed<DataTableHeader[]>(() => props.table.columns.map((column) => ({
    align: resolveAlignment(column.alignment),
    key: column.name,
    sortable: column.sortable,
    title: column.label,
    value: column.name,
})));
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
            v-model:sort-by="sortBy"
            must-sort
            fixed-header
            show-first-last-page
            @update:options="loadTable"
        >
            <template
                v-for="column in table.columns"
                :key="column.name"
                #[`item.${column.name}`]="{ value }"
            >
                {{ value === null ? translate('frontend.data_table.empty_value') : value }}
            </template>
        </v-data-table-server>
    </v-card>
</template>

<style scoped>
.data-table :deep(.v-table__wrapper > table > thead > tr > th) {
    background: color-mix(in srgb, rgb(var(--v-theme-surface)) 90%, black);
    border: 0;
    border-bottom: 2px solid rgba(var(--v-border-color), calc(var(--v-border-opacity) * 2));
    box-shadow: none;
}

.data-table :deep(.v-table__wrapper > table > tbody > tr:hover > td) {
    background: color-mix(in srgb, rgb(var(--v-theme-primary)) 25%, white);
}
</style>
