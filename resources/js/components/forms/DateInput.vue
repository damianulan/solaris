<script setup lang="ts">
import { computed } from 'vue';

defineOptions({ inheritAttrs: false });

const props = defineProps<{
    modelValue?: string | null;
}>();

const emit = defineEmits<{
    'update:modelValue': [value: string | null];
}>();

const date = computed<Date | null>({
    get: () => props.modelValue ? new Date(`${props.modelValue}T00:00:00`) : null,
    set: (value) => emit('update:modelValue', value ? formatDate(value) : null),
});

function formatDate(value: Date): string {
    const year = value.getFullYear();
    const month = String(value.getMonth() + 1).padStart(2, '0');
    const day = String(value.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}
</script>

<template>
    <v-date-input
        v-model="date"
        v-bind="$attrs"
        variant="outlined"
    />
</template>
