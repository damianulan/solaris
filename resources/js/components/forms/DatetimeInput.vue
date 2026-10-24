<script setup lang="ts">
defineOptions({ inheritAttrs: false });

const model = defineModel<string | null>({ default: null });
</script>

<template>
    <v-text-field
        v-model="model"
        v-bind="$attrs"
        type="datetime-local"
        variant="outlined"
    />
</template>
