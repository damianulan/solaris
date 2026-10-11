<script setup lang="ts">
import { computed } from 'vue';

import type { NavigationElement, NavigationGroup } from '../../lib/types';
import AppNavigationLink from './AppNavigationLink.vue';

const props = defineProps<{
    item: NavigationElement;
}>();

const isNavigationGroup = (item: NavigationElement): item is NavigationGroup => 'items' in item;

const navigationGroup = computed(() => isNavigationGroup(props.item) ? props.item : null);
const navigationLink = computed(() => isNavigationGroup(props.item) ? null : props.item);

const isActive = computed(() => navigationGroup.value !== null && navigationGroup.value.active);
</script>

<template>
    <v-menu v-if="navigationGroup" open-on-hover submenu>
        <template #activator="{ props: activatorProps }">
            <v-list-item
                v-bind="activatorProps"
                :active="isActive"
                :prepend-icon="navigationGroup.icon ?? undefined"
                :title="navigationGroup.title"
                :disabled="navigationGroup.disabled"
                color="accent"
                append-icon="bi-chevron-right"
            />
        </template>

        <v-list density="comfortable" min-width="220" nav>
            <AppNavigationItem
                v-for="(childItem, index) in navigationGroup.items"
                :key="`${childItem.title}-${index}`"
                :item="childItem"
            />
        </v-list>
    </v-menu>

    <AppNavigationLink v-else-if="navigationLink" v-bind="navigationLink" />
</template>
