<script setup lang="ts">
defineOptions({ inheritAttrs: false });

interface RadioOption {
    label: string;
    value: boolean | number | string;
}

defineProps<{
    options: readonly RadioOption[];
}>();

const model = defineModel<boolean | number | string | null>({ default: null });
</script>

<template>
    <v-radio-group
        v-model="model"
        v-bind="$attrs"
    >
        <v-radio
            v-for="option in options"
            :key="String(option.value)"
            :label="option.label"
            :value="option.value"
        />
    </v-radio-group>
</template>
