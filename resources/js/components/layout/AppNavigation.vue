<script setup lang="ts">
import AppNavigationLink from './AppNavigationLink.vue';

defineProps<{
    isOpen: boolean;
}>();

const emit = defineEmits<{
    'update:isOpen': [value: boolean];
}>();
</script>

<template>
    <v-navigation-drawer
        :model-value="isOpen"
        @update:model-value="emit('update:isOpen', $event)"
    >
        <v-list nav density="comfortable">
            <AppNavigationLink href="/" icon="bi-house" title="Home" />
        </v-list>
    </v-navigation-drawer>
</template>
