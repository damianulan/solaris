<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { VBtn } from 'vuetify/components';

import type { NavigationElement, NavigationGroup } from '../../../lib/types';
import AppNavigationItem from './AppNavigationItem.vue';

const props = defineProps<{
    item: NavigationElement;
}>();

const isNavigationGroup = (item: NavigationElement): item is NavigationGroup => 'items' in item;

const navigationGroup = computed(() => isNavigationGroup(props.item) ? props.item : null);
const navigationLink = computed(() => isNavigationGroup(props.item) ? null : props.item);
</script>

<template>
    <v-menu
        v-if="navigationGroup"
        location="bottom start"
        open-on-hover
    >
        <template #activator="{ props: activatorProps }">
            <v-btn
                v-bind="activatorProps"
                append-icon="bi-chevron-down"
                :class="{ 'bg-primary text-white': navigationGroup.active }"
                :disabled="navigationGroup.disabled"
                :prepend-icon="navigationGroup.icon ?? undefined"
                :text="navigationGroup.title"
                variant="text"
            />
        </template>

        <v-list
            class="pa-2"
            density="comfortable"
            min-width="240"
            nav
        >
            <AppNavigationItem
                v-for="(childItem, index) in navigationGroup.items"
                :key="`${childItem.title}-${index}`"
                :item="childItem"
            />
        </v-list>
    </v-menu>

    <Link
        v-else-if="navigationLink"
        :as="VBtn"
        :active="navigationLink.active"
        active-class="bg-primary text-white"
        :aria-current="navigationLink.active ? 'page' : undefined"
        :disabled="navigationLink.disabled"
        :href="navigationLink.link"
        prefetch
        :prepend-icon="navigationLink.icon ?? undefined"
        :text="navigationLink.title"
        variant="text"
        view-transition
    />
</template>
