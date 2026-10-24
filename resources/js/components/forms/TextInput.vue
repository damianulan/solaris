<script setup lang="ts">
defineOptions({ inheritAttrs: false });

const model = defineModel<string | null>({ default: null });
</script>

<template>
    <v-text-field
        v-model="model"
        v-bind="$attrs"
        variant="outlined"
        density="default"
        :rounded="false"
        color="text-primary"
    />
</template>
