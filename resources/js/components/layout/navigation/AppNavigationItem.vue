<script setup lang="ts">
import { computed } from 'vue';

import type { NavigationElement, NavigationGroup } from '../../../lib/types';
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
    <v-list-group
        v-if="navigationGroup"
        :value="`${navigationGroup.title}-${navigationGroup.priority}`"
    >
        <template #activator="{ props: activatorProps }">
            <v-list-item
                v-bind="activatorProps"
                :active="isActive"
                active-class="bg-topbar"
                color="#FFFFFF"
                density="comfortable"
                :disabled="navigationGroup.disabled"
                :prepend-icon="navigationGroup.icon ?? undefined"
                rounded="lg"
                :title="navigationGroup.title"
            />
        </template>

        <AppNavigationItem
            v-for="(childItem, index) in navigationGroup.items"
            :key="`${childItem.title}-${index}`"
            :item="childItem"
        />
    </v-list-group>

    <AppNavigationLink v-else-if="navigationLink" v-bind="navigationLink" />
</template>
