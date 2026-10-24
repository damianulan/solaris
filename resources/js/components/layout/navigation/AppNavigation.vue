<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';

import AppNavigationItem from './AppNavigationItem.vue';

defineProps<{
    isOpen: boolean;
}>();

const page = usePage();
</script>

<template>
    <v-navigation-drawer
        border="e"
        color="background"
        elevation="0"
        permanent
        fluid
        :rail="!isOpen"
        width="276"
    >
        <v-list
            :class="['py-3', isOpen ? 'px-3' : 'px-2']"
            density="comfortable"
            nav
        >
            <AppNavigationItem
                v-for="(navigationItem, index) in page.props.sidebar"
                :key="`${navigationItem.title}-${index}`"
                :item="navigationItem"
            />
        </v-list>
    </v-navigation-drawer>
</template>
