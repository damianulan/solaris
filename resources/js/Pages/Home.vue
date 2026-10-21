<script setup lang="ts">
import DataTable from '../components/data-tables/DataTable.vue';
import AppLayout from '../components/layout/AppLayout.vue';
import type { DataTableBlueprint } from '../lib/types';

defineProps<{
    table: DataTableBlueprint;
}>();
</script>

<template>
    <AppLayout>
        <DataTable :table="table" />
    </AppLayout>
</template>
