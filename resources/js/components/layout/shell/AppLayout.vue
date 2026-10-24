<script setup lang="ts">
import { usePage, Head } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue';

import type { Banner } from '../../../lib/types';
import AppAlerts from '../feedback/AppAlerts.vue';
import AppFooter from './AppFooter.vue';
import AppHeader from './AppHeader.vue';
import AppHorizontalNavigation from '../navigation/AppHorizontalNavigation.vue';
import AppNavigation from '../navigation/AppNavigation.vue';
import AppSnackbars from '../feedback/AppSnackbars.vue';
import AppTopBanner from '../feedback/AppTopBanner.vue';

const props = withDefaults(defineProps<{
    navigation?: 'horizontal' | 'vertical';
}>(), {
    navigation: 'horizontal',
});

const navigationStorageKey = 'solaris.navigation.isOpen';
const isNavigationOpen = ref(true);
const page = usePage();
const banner = computed<Banner>(() => {
    if (typeof page.props.banner === 'string') {
        return { text: page.props.banner };
    }

    return page.props.banner ?? {};
});

onMounted(() => {
    const storedNavigationState = window.localStorage.getItem(navigationStorageKey);

    isNavigationOpen.value = storedNavigationState === null || storedNavigationState === 'true';
});

watch(isNavigationOpen, (isOpen) => {
    if (typeof window !== 'undefined') {
        window.localStorage.setItem(navigationStorageKey, String(isOpen));
    }
});
</script>

<template>
    <v-app>
        <AppTopBanner v-bind="banner" />
        <AppHeader
            :show-navigation-toggle="props.navigation === 'vertical'"
            @toggle-navigation="isNavigationOpen = !isNavigationOpen"
        />
        <AppNavigation
            v-if="props.navigation === 'vertical'"
            :is-open="isNavigationOpen"
        />
        <AppHorizontalNavigation v-else />
        <Head :title="page.props.page.header ?? undefined" />

        <v-main>
            <v-container class="py-2 px-6" :fluid="props.navigation === 'vertical'">
                <div v-if="page.props.page.header" class="pb-4 pt-2">
                    <div class="text-headline-large font-weight-medium text-heading">{{ page.props.page.header }}</div>
                    <div v-if="page.props.page.description" class="text-body-medium text-medium-emphasis font-italic">{{ page.props.page.description }}</div>
                </div>
                <AppAlerts />

                <slot />
            </v-container>
        </v-main>
        <AppSnackbars />

        <AppFooter />
    </v-app>
</template>
