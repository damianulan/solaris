<script setup lang="ts">
defineOptions({ inheritAttrs: false });

const model = defineModel<boolean | number | string | null>({ default: null });
</script>

<template>
    <v-select
        v-model="model"
        v-bind="$attrs"
        variant="outlined"
    />
</template>
