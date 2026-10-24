<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

import type { FormItem, FormSchema, FormValue, FormValues } from '../../lib/forms';
import ResetButton from '../buttons/ResetButton.vue';
import SubmitButton from '../buttons/SubmitButton.vue';
import CheckboxInput from './CheckboxInput.vue';
import ColorInput from './ColorInput.vue';
import DateInput from './DateInput.vue';
import DatetimeInput from './DatetimeInput.vue';
import FileInput from './FileInput.vue';
import FilepondInput from './FilepondInput.vue';
import MultiselectInput from './MultiselectInput.vue';
import NumberInput from './NumberInput.vue';
import RadioInput from './RadioInput.vue';
import RangeInput from './RangeInput.vue';
import SelectInput from './SelectInput.vue';
import SwitchInput from './SwitchInput.vue';
import TextareaInput from './TextareaInput.vue';
import TextInput from './TextInput.vue';

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

    if (value instanceof File || (Array.isArray(value) && value.every((item) => item instanceof File))) {
        return value;
    }

    return null;
}

function booleanValue(name: string): boolean {
    return form[name] === true;
}

function numberValue(name: string): number | null {
    const value = form[name];

    return typeof value === 'number' ? value : null;
}

function rangeValue(name: string): number {
    return numberValue(name) ?? 0;
}

function stringValue(name: string): string | null {
    const value = form[name];

    return typeof value === 'string' ? value : null;
}

function stringArrayValue(name: string): string[] {
    const value = form[name];

    return Array.isArray(value) && value.every((item) => typeof item === 'string') ? value : [];
}

function setValue(item: FormItem, value: unknown): void {
    if (item.type === 'number') {
        const normalizedValue = value === '' || value === null ? null : Number(value);

        if (normalizedValue !== null && Number.isNaN(normalizedValue)) {
            return;
        }

        form[item.name] = normalizedValue;
        form.clearErrors(item.name);

        return;
    }

    if (item.type === 'range') {
        const normalizedValue = Number(value);

        if (Number.isNaN(normalizedValue)) {
            return;
        }

        form[item.name] = normalizedValue;
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
        || (Array.isArray(value) && value.every((item) => item instanceof File || typeof item === 'string'));
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
                <TextareaInput
                    v-if="item.type === 'textarea'"
                    :autocomplete="item.autocomplete"
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    :label="item.label"
                    :model-value="stringValue(item.name)"
                    :name="item.name"
                    :placeholder="item.placeholder"
                    :rows="item.rows ?? 4"
                    @update:model-value="setValue(item, $event)"
                />

                <SelectInput
                    v-else-if="item.type === 'select'"
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    item-title="label"
                    item-value="value"
                    :items="item.options"
                    :label="item.label"
                    :model-value="stringValue(item.name)"
                    :name="item.name"
                    @update:model-value="setValue(item, $event)"
                />

                <MultiselectInput
                    v-else-if="item.type === 'multiselect'"
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    item-title="label"
                    item-value="value"
                    :items="item.options"
                    :label="item.label"
                    :model-value="stringArrayValue(item.name)"
                    :name="item.name"
                    @update:model-value="setValue(item, $event)"
                />

                <RadioInput
                    v-else-if="item.type === 'radio'"
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    :inline="item.inline"
                    :label="item.label"
                    :model-value="stringValue(item.name)"
                    :name="item.name"
                    :options="item.options"
                    @update:model-value="setValue(item, $event)"
                />

                <SwitchInput
                    v-else-if="item.type === 'switch'"
                    :color="item.color"
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    :inset="item.inset"
                    :label="item.label"
                    :model-value="booleanValue(item.name)"
                    :name="item.name"
                    @update:model-value="setValue(item, $event)"
                />

                <CheckboxInput
                    v-else-if="item.type === 'checkbox'"
                    :color="item.color"
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    :label="item.label"
                    :model-value="booleanValue(item.name)"
                    :name="item.name"
                    @update:model-value="setValue(item, $event)"
                />

                <RangeInput
                    v-else-if="item.type === 'range'"
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    :label="item.label"
                    :max="item.max"
                    :min="item.min"
                    :model-value="rangeValue(item.name)"
                    :name="item.name"
                    :show-ticks="item.showTicks"
                    :step="item.step ?? 1"
                    :thumb-label="item.thumbLabel ?? 'always'"
                    @update:model-value="setValue(item, $event)"
                />

                <FileInput
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
                    @update:model-value="setFileValue(item, $event)"
                />

                <FilepondInput
                    v-else-if="item.type === 'filepond'"
                    :accepted-file-types="item.acceptedFileTypes"
                    :clear="form.recentlySuccessful"
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    :label="item.label"
                    :max-file-size="item.maxFileSize"
                    :model-value="stringValue(item.name)"
                    :name="item.name"
                    :server-url="item.serverUrl"
                    @update:model-value="setValue(item, $event)"
                />

                <NumberInput
                    v-else-if="item.type === 'number'"
                    :autocomplete="item.autocomplete"
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    :label="item.label"
                    :max="item.max"
                    :min="item.min"
                    :model-value="numberValue(item.name)"
                    :name="item.name"
                    :placeholder="item.placeholder"
                    :step="item.step"
                    @update:model-value="setValue(item, $event)"
                />

                <DateInput
                    v-else-if="item.type === 'date'"
                    :autocomplete="item.autocomplete"
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    :label="item.label"
                    :max="item.max"
                    :min="item.min"
                    :model-value="stringValue(item.name)"
                    :name="item.name"
                    @update:model-value="setValue(item, $event)"
                />

                <DatetimeInput
                    v-else-if="item.type === 'datetime' || item.type === 'datetime-local'"
                    :autocomplete="item.autocomplete"
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    :label="item.label"
                    :max="item.max"
                    :min="item.min"
                    :model-value="stringValue(item.name)"
                    :name="item.name"
                    @update:model-value="setValue(item, $event)"
                />

                <ColorInput
                    v-else-if="item.type === 'color'"
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    :label="item.label"
                    :model-value="stringValue(item.name)"
                    :name="item.name"
                    @update:model-value="setValue(item, $event)"
                />

                <TextInput
                    v-else
                    :autocomplete="item.autocomplete"
                    :error-messages="errorMessages(item.name)"
                    :hint="item.hint"
                    :label="item.label"
                    :model-value="stringValue(item.name)"
                    :name="item.name"
                    :placeholder="item.placeholder"
                    :type="item.type"
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
