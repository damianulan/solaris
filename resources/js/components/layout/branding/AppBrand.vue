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
    <div class="app-brand">
        <BrandLogo :color="props.color" />
        <BrandTitle :color="props.color" />
    </div>
</template>

<style scoped>
.app-brand {
    align-items: center;
    display: flex;
    gap: 10px;
}
</style>
