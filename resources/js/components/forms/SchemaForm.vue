<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

import type { FormItem, FormSchema, FormValue, FormValues } from '../../lib/forms';
import ResetButton from '../buttons/ResetButton.vue';
import SubmitButton from '../buttons/SubmitButton.vue';

const props = defineProps<{
    schema: FormSchema;
}>();

const schema = computed(() => ({
    ...props.schema,
    resetLabel: props.schema.resetLabel ?? 'Reset',
    submitLabel: props.schema.submitLabel ?? 'Submit',
}));

const initialValues = schema.value.items.reduce<FormValues>((values, item) => {
    values[item.name] = item.defaultValue;

    return values;
}, {});

const form = useForm<FormValues>(initialValues);

function errorMessages(name: string): string[] {
    const error = form.errors[name];

    if (Array.isArray(error)) {
        return error;
    }

    return error ? [error] : [];
}

function fileValue(name: string): File | File[] | null {
    const value = form[name];

    if (value instanceof File || Array.isArray(value)) {
        return value;
    }

    return null;
}

function numberValue(name: string): number | undefined {
    const value = form[name];

    return typeof value === 'number' ? value : undefined;
}

function setValue(item: FormItem, value: unknown): void {
    if (item.type === 'number' || item.type === 'range') {
        const numberValue = value === '' || value === null ? null : Number(value);

        if (numberValue === null || Number.isNaN(numberValue)) {
            return;
        }

        form[item.name] = numberValue;
        form.clearErrors(item.name);

        return;
    }

    if (! isFormValue(value)) {
        return;
    }

    form[item.name] = value;
    form.clearErrors(item.name);
}

function setFileValue(item: Extract<FormItem, { type: 'file' }>, value: File | File[] | null): void {
    const files = Array.isArray(value) ? value : value ? [value] : [];

    form[item.name] = item.multiple ? files : (files[0] ?? null);
    form.clearErrors(item.name);
}

function isFormValue(value: unknown): value is FormValue {
    return value === null
        || typeof value === 'boolean'
        || typeof value === 'number'
        || typeof value === 'string'
        || value instanceof File
        || (Array.isArray(value) && value.every((item) => item instanceof File));
}

function submit(): void {
    if (schema.value.method !== 'post' && schema.value.items.some((item) => item.type === 'file')) {
        form.transform((data) => ({
            ...data,
            _method: schema.value.method,
        })).post(schema.value.action, {
            forceFormData: true,
            preserveScroll: true,
        });

        return;
    }

    form.submit(schema.value.method, schema.value.action, {
        forceFormData: schema.value.items.some((item) => item.type === 'file'),
        preserveScroll: true,
    });
}

function reset(): void {
    form.resetAndClearErrors();
}
</script>

<template>
    <form novalidate @reset.prevent="reset" @submit.prevent="submit">
        <v-row>
            <v-col
                v-for="item in schema.items"
                :key="item.name"
                cols="12"
                :md="item.columns ?? 12"
            >
                <v-textarea
                    v-if="item.type === 'textarea'"
                    :autocomplete="item.autocomplete"
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    :label="item.label"
                    :model-value="form[item.name]"
                    :name="item.name"
                    :placeholder="item.placeholder"
                    :rows="item.rows ?? 4"
                    variant="outlined"
                    @update:model-value="setValue(item, $event)"
                />

                <v-select
                    v-else-if="item.type === 'select'"
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    item-title="label"
                    item-value="value"
                    :items="item.options"
                    :label="item.label"
                    :model-value="form[item.name]"
                    :name="item.name"
                    variant="outlined"
                    @update:model-value="setValue(item, $event)"
                />

                <v-radio-group
                    v-else-if="item.type === 'radio'"
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    :inline="item.inline"
                    :label="item.label"
                    :model-value="form[item.name]"
                    :name="item.name"
                    @update:model-value="setValue(item, $event)"
                >
                    <v-radio
                        v-for="option in item.options"
                        :key="option.value"
                        :label="option.label"
                        :value="option.value"
                    />
                </v-radio-group>

                <v-switch
                    v-else-if="item.type === 'switch'"
                    :color="item.color"
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    :inset="item.inset"
                    :label="item.label"
                    :model-value="form[item.name]"
                    :name="item.name"
                    @update:model-value="setValue(item, $event)"
                />

                <v-checkbox
                    v-else-if="item.type === 'checkbox'"
                    :color="item.color"
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    :label="item.label"
                    :model-value="form[item.name]"
                    :name="item.name"
                    @update:model-value="setValue(item, $event)"
                />

                <v-slider
                    v-else-if="item.type === 'range'"
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    :label="item.label"
                    :max="item.max"
                    :min="item.min"
                    :model-value="numberValue(item.name)"
                    :name="item.name"
                    :show-ticks="item.showTicks"
                    :step="item.step ?? 1"
                    :thumb-label="item.thumbLabel ?? 'always'"
                    @update:model-value="setValue(item, $event)"
                />

                <v-file-input
                    v-else-if="item.type === 'file'"
                    :accept="item.accept"
                    clearable
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    :label="item.label"
                    :model-value="fileValue(item.name)"
                    :multiple="item.multiple"
                    :name="item.name"
                    :show-size="item.showSize"
                    variant="outlined"
                    @update:model-value="setFileValue(item, $event)"
                />

                <v-text-field
                    v-else-if="item.type === 'number'"
                    :autocomplete="item.autocomplete"
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    :label="item.label"
                    :max="item.max"
                    :min="item.min"
                    :model-value="form[item.name]"
                    :name="item.name"
                    :placeholder="item.placeholder"
                    :step="item.step"
                    type="number"
                    variant="outlined"
                    @update:model-value="setValue(item, $event)"
                />

                <v-text-field
                    v-else-if="item.type === 'date'
                        || item.type === 'datetime-local'
                        || item.type === 'month'
                        || item.type === 'time'
                        || item.type === 'week'"
                    :autocomplete="item.autocomplete"
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    :label="item.label"
                    :max="item.max"
                    :min="item.min"
                    :model-value="form[item.name]"
                    :name="item.name"
                    :type="item.type"
                    variant="outlined"
                    @update:model-value="setValue(item, $event)"
                />

                <v-text-field
                    v-else
                    :autocomplete="item.autocomplete"
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    :label="item.label"
                    :model-value="form[item.name]"
                    :name="item.name"
                    :placeholder="item.placeholder"
                    :type="item.type"
                    variant="outlined"
                    @update:model-value="setValue(item, $event)"
                />
            </v-col>
        </v-row>

        <v-progress-linear
            v-if="form.progress"
            class="mb-4"
            color="primary"
            :model-value="form.progress.percentage"
        />

        <v-alert
            v-if="form.recentlySuccessful && schema.successMessage"
            class="mb-4"
            color="success"
            type="success"
            variant="tonal"
        >
            {{ schema.successMessage }}
        </v-alert>

        <div class="d-flex flex-wrap ga-3">
            <SubmitButton :loading="form.processing">
                {{ schema.submitLabel }}
            </SubmitButton>

            <ResetButton :disabled="form.processing">
                {{ schema.resetLabel }}
            </ResetButton>
        </div>
    </form>
</template>
