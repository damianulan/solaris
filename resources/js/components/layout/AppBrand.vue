<script setup lang="ts">
import BrandLogo from './BrandLogo.vue';
import BrandTitle from './BrandTitle.vue';

const props = withDefaults(defineProps<{
    color?: string;
}>(), {
    color: 'brand',
});
</script>

<template>
    <span class="app-brand">
        <BrandLogo :color="props.color" />
        <BrandTitle :color="props.color" />
    </span>
</template>

<style scoped>
.app-brand {
    display: inline-flex;
    align-items: center;
    gap: 10px;
}
</style>
