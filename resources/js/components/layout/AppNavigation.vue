<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';

import AppNavigationItem from './AppNavigationItem.vue';

defineProps<{
    isOpen: boolean;
}>();

const emit = defineEmits<{
    'update:isOpen': [value: boolean];
}>();

const page = usePage();
</script>

<template>
    <v-navigation-drawer
        border="e"
        color="surface"
        elevation="0"
        fluid
        :model-value="isOpen"
        width="276"
        @update:model-value="emit('update:isOpen', $event)"
    >
        <v-list
            class="pa-3"
            color="accent"
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
