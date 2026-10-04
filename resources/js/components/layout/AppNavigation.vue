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
        class="py-2"
        fluid
        :model-value="isOpen"
        @update:model-value="emit('update:isOpen', $event)"
    >
        <v-list nav density="comfortable">
            <AppNavigationItem
                v-for="(navigationItem, index) in page.props.sidebar"
                :key="`${navigationItem.title}-${index}`"
                :item="navigationItem"
            />
        </v-list>
    </v-navigation-drawer>
</template>
