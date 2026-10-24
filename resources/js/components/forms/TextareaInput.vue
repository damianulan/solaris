<script setup lang="ts">
defineOptions({ inheritAttrs: false });

const model = defineModel<string | null>({ default: null });
</script>

<template>
    <v-textarea
        v-model="model"
        v-bind="$attrs"
        variant="outlined"
    />
</template>
