import { router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import type { DataTableHeader } from 'vuetify';

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

type Translate = (key: string) => string;

export function useDataTable(table: () => DataTableBlueprint, translate: Translate) {
    const page = usePage();
    const isLoading = ref(false);
    const selectedItems = ref<Array<number | string>>([]);
    const resolvedTable = computed(table);
    const selectableColumn = computed(() => resolvedTable.value.columns.find((column) => column.type === 'selectable'));
    const actionColumns = computed(() => resolvedTable.value.columns.filter((column) => column.type === 'action'));
    const dataColumns = computed(() => resolvedTable.value.columns.filter((column) => column.type === 'default'));
    const headers = computed<DataTableHeader[]>(() => resolvedTable.value.columns.map((column) => column.type === 'selectable'
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
    const itemsPerPageOptions = computed(() => resolvedTable.value.pagination.map((value) => value === -1
        ? { title: translate('frontend.data_table.all_items'), value }
        : value));
    const sortBy = ref<DataTableSortItem[]>(initialSort());

    function queryParameters(): URLSearchParams {
        return new URLSearchParams(page.url.split('?')[1] ?? '');
    }

    function initialSort(): DataTableSortItem[] {
        const parameters = queryParameters();
        const key = parameters.get('sortBy');
        const column = resolvedTable.value.columns.find((candidate) => candidate.name === key);

        if (!column?.sortable) {
            return [];
        }

        return [{
            key: column.name,
            order: parameters.get('sortDirection')?.toLowerCase() === 'desc' ? 'desc' : 'asc',
        }];
    }

    function loadTable(options: DataTableOptions): void {
        const paginator = resolvedTable.value.paginator;
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

    return {
        actionColumns,
        dataColumns,
        headers,
        isLoading,
        itemsPerPageOptions,
        loadTable,
        selectableColumn,
        selectedItems,
        sortBy,
    };
}

function resolveAlignment(alignment: DataTableColumn['alignment']): DataTableHeader['align'] {
    if (alignment === 'right') {
        return 'end';
    }

    if (alignment === 'center') {
        return 'center';
    }

    return 'start';
}
