<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';

const props = withDefaults(defineProps<{
    color?: string;
}>(), {
    color: 'brand',
});

const page = usePage();
</script>

<template>
    <span
        :class="`text-${props.color}`"
        class="font-weight-semibold text-title-medium text-uppercase"
        style="letter-spacing: 0.3rem;"
    >
        {{ page.props.page.title }}
    </span>
</template>
