<script setup lang="ts">
import { computed, reactive, watch } from 'vue';

import type {
    DataTableFilterDefinition,
    DataTableFilterOption,
    DataTableFilterValue,
} from '../../lib/types';
import { useTranslations } from '../../lib/translations';
import ResetButton from '../buttons/ResetButton.vue';
import SubmitButton from '../buttons/SubmitButton.vue';
import MultiselectInput from '../forms/MultiselectInput.vue';
import SelectInput from '../forms/SelectInput.vue';
import TextInput from '../forms/TextInput.vue';

const props = defineProps<{
    filters: DataTableFilterDefinition[];
    loading: boolean;
}>();

const emit = defineEmits<{
    submit: [values: Record<string, DataTableFilterValue>];
}>();

const { translate } = useTranslations();
const values = reactive<Record<string, DataTableFilterValue>>({});
const hasActiveFilters = computed(() => Object.values(values).some((value) => (
    Array.isArray(value) ? value.length > 0 : value !== null && value !== ''
)));

watch(
    () => props.filters,
    (filters) => {
        const activeSlugs = new Set(filters.map(({ filter }) => filter.slug));

        Object.keys(values).forEach((slug) => {
            if (! activeSlugs.has(slug)) {
                delete values[slug];
            }
        });

        filters.forEach(({ filter }) => {
            values[filter.slug] = Array.isArray(filter.value)
                ? [...filter.value]
                : filter.value;
        });
    },
    { deep: true, immediate: true },
);

function textValue(slug: string): string {
    const value = values[slug];

    return typeof value === 'string' ? value : '';
}

function multiselectValue(slug: string): string[] {
    const value = values[slug];

    return Array.isArray(value) ? value : [];
}

function options(items: DataTableFilterOption[]): Array<{ label: string; value: string }> {
    return items.map((item) => ({
        label: item.label,
        value: String(item.value),
    }));
}

function setTextValue(slug: string, value: unknown): void {
    values[slug] = typeof value === 'string' ? value : null;
}

function setSelectValue(slug: string, value: unknown): void {
    values[slug] = typeof value === 'string' ? value : null;
}

function setMultiselectValue(slug: string, value: unknown): void {
    values[slug] = Array.isArray(value)
        ? value.filter((item): item is string => typeof item === 'string')
        : [];
}

function submit(): void {
    emit('submit', Object.fromEntries(
        Object.entries(values).map(([slug, value]) => [
            slug,
            Array.isArray(value) ? [...value] : value,
        ]),
    ));
}

function reset(): void {
    props.filters.forEach(({ filter }) => {
        values[filter.slug] = filter.type === 'multiselect' ? [] : null;
    });

    submit();
}
</script>

<template>
    <form class="data-table-filters" @reset.prevent="reset" @submit.prevent="submit">
        <v-row>
            <v-col
                v-for="definition in filters"
                :key="definition.filter.slug"
                cols="12"
                :md="definition.col"
            >
                <TextInput
                    v-if="definition.filter.type === 'text'"
                    :disabled="loading"
                    hide-details
                    :label="definition.filter.label"
                    :model-value="textValue(definition.filter.slug)"
                    @update:model-value="setTextValue(definition.filter.slug, $event)"
                />

                <MultiselectInput
                    v-else-if="definition.filter.type === 'multiselect'"
                    clearable
                    :disabled="loading"
                    hide-details
                    item-title="label"
                    item-value="value"
                    :items="options(definition.filter.options)"
                    :label="definition.filter.label"
                    :model-value="multiselectValue(definition.filter.slug)"
                    @update:model-value="setMultiselectValue(definition.filter.slug, $event)"
                />

                <SelectInput
                    v-else
                    clearable
                    :disabled="loading"
                    hide-details
                    item-title="label"
                    item-value="value"
                    :items="options(definition.filter.options)"
                    :label="definition.filter.label"
                    :model-value="textValue(definition.filter.slug)"
                    @update:model-value="setSelectValue(definition.filter.slug, $event)"
                />
            </v-col>

            <v-col class="d-flex justify-end ga-2" cols="12">
                <ResetButton
                    :disabled="loading || !hasActiveFilters"
                    prepend-icon="bi-x-circle"
                >
                    {{ translate('frontend.data_table.filters.reset') }}
                </ResetButton>

                <SubmitButton :loading="loading" prepend-icon="bi-funnel-fill">
                    {{ translate('frontend.data_table.filters.submit') }}
                </SubmitButton>
            </v-col>
        </v-row>
    </form>
</template>

<style scoped>
.data-table-filters {
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));
}
</style>
