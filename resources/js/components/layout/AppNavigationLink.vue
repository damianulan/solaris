<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

import type { NavigationLink } from '../../lib/types';

defineProps<NavigationLink>();
</script>

<template>
    <Link :href="link">
        <v-list-item density="compact"
            :active="active"
            :prepend-icon="icon ?? undefined"
            :title="title"
            color="accent"
        />
    </Link>
</template>
