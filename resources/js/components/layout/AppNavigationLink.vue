<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { VListItem } from 'vuetify/components';

import type { NavigationLink } from '../../lib/types';

defineProps<NavigationLink>();
</script>

<template>
    <Link
        :as="VListItem"
        :active="active"
        :aria-current="active ? 'page' : undefined"
        color="accent"
        density="comfortable"
        :disabled="disabled"
        :href="link"
        :hover="true"
        :link="true"
        :nav="true"
        prefetch
        :prepend-icon="icon ?? undefined"
        prepend-gap="1rem"
        rounded="lg"
        :title="title"
        variant="text"
        view-transition
    />
</template>
