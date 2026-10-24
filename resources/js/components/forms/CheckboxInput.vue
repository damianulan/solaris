<script setup lang="ts">
defineOptions({ inheritAttrs: false });

const model = defineModel<boolean>({ default: false });
</script>

<template>
    <v-checkbox
        v-model="model"
        v-bind="$attrs"
    />
</template>
