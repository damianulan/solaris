<script setup lang="ts">
defineOptions({ inheritAttrs: false });

const model = defineModel<File | File[] | null>({ default: null });
</script>

<template>
    <v-file-input
        v-model="model"
        v-bind="$attrs"
        clearable
        variant="outlined"
    />
</template>
