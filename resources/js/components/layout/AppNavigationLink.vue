<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import type { NavigationLink } from '../../lib/types';

const props = defineProps<NavigationLink>();

const page = usePage();
const isActive = computed(() => page.url === props.href);
</script>

<template>
    <Link :href="href">
        <v-list-item :active="isActive" :prepend-icon="icon" :title="title" active-color="primary" />
    </Link>
</template>
