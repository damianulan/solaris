<script setup lang="ts">
defineOptions({ inheritAttrs: false });

const model = defineModel<Array<number | string>>({ default: () => [] });
</script>

<template>
    <v-select
        v-model="model"
        v-bind="$attrs"
        chips
        multiple
        variant="outlined"
    />
</template>
