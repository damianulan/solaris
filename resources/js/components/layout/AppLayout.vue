<script setup lang="ts">
import { ref, watch } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import AppFooter from './AppFooter.vue';
import AppHeader from './AppHeader.vue';
import AppNavigation from './AppNavigation.vue';
import AppSnackbars from './AppSnackbars.vue';

const navigationStorageKey = 'solaris.navigation.isOpen';
const storedNavigationState = window.localStorage.getItem(navigationStorageKey);
const isNavigationOpen = ref(storedNavigationState === null || storedNavigationState === 'true');
const page = usePage();

watch(isNavigationOpen, (isOpen) => {
    window.localStorage.setItem(navigationStorageKey, String(isOpen));
});
</script>

<template>
    <v-app>
        <AppHeader @toggle-navigation="isNavigationOpen = !isNavigationOpen" />
        <AppNavigation v-model:is-open="isNavigationOpen" />
        <Head :title="page.props.page.header ?? undefined" />

        <v-main>
            <v-container class="py-2" fluid>
                <div v-if="page.props.page.header" class="pb-4 pt-2">
                    <div class="text-headline-large font-weight-medium text-accent">{{ page.props.page.header }}</div>
                    <div v-if="page.props.page.description" class="text-body-small text-medium-emphasis font-italic">{{ page.props.page.description }}</div>
                </div>

                <slot />
            </v-container>
        </v-main>
        <AppSnackbars />

        <AppFooter />
    </v-app>
</template>
