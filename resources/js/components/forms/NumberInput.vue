<script setup lang="ts">
defineOptions({ inheritAttrs: false });

const model = defineModel<number | null>({ default: null });
</script>

<template>
    <v-number-input
        v-model="model"
        v-bind="$attrs"
        variant="outlined"
    />
</template>
