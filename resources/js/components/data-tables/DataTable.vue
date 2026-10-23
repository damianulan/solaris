<script setup lang="ts">
import { useTranslations } from '../../lib/translations';
import type { DataTableBlueprint } from '../../lib/types';
import ConfirmationDialog from '../dialogs/ConfirmationDialog.vue';
import DataTableActionButtons from './DataTableActionButtons.vue';
import { useDataTable } from './useDataTable';
import { useDataTableActions } from './useDataTableActions';

const props = defineProps<{
    table: DataTableBlueprint;
}>();

defineSlots<{
    actions?: (props: { selectedItems: Array<number | string> }) => unknown;
}>();

const { translate } = useTranslations();
const {
    actionColumns,
    dataColumns,
    headers,
    isLoading,
    itemsPerPageOptions,
    loadTable,
    selectableColumn,
    selectedItems,
    sortBy,
} = useDataTable(() => props.table, translate);
const {
    confirmAction,
    isConfirmationDialogOpen,
    pendingAction,
    performAction,
} = useDataTableActions();
</script>

<template>
    <v-card class="data-table-card" border flat>
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
                <DataTableActionButtons :value="value" @action="performAction" />
            </template>
        </v-data-table-server>

        <ConfirmationDialog
            v-model="isConfirmationDialogOpen"
            :cancel-label="translate('frontend.data_table.confirmation.cancel')"
            :color="pendingAction?.color"
            :confirm-icon="pendingAction?.icon"
            :confirm-label="pendingAction?.title ?? translate('frontend.data_table.confirmation.confirm')"
            :icon="pendingAction?.icon"
            :message="pendingAction?.confirm ?? ''"
            :title="translate('frontend.data_table.confirmation.title')"
            @confirm="confirmAction"
        />
    </v-card>
</template>

<style scoped>
.data-table-card {
    border-left-width: 0;
    border-right-width: 0;
}

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
