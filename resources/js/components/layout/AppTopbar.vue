<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useTheme } from 'vuetify';

import type { ThemeVariant, UserContext } from '../../lib/types';

const props = defineProps<{
    user?: UserContext;
}>();

const theme = useTheme();
const page = usePage();

const userInitial = computed(() => props.user?.name.charAt(0).toUpperCase() ?? 'G');
const isDarkTheme = computed(() => theme.current.value.dark);
const themeSwitcherIcon = computed(() => isDarkTheme.value ? 'bi-sun' : 'bi-moon-stars');
const themeSwitcherLabel = computed(() => isDarkTheme.value ? 'Switch to light theme' : 'Switch to dark theme');

const switchTheme = (event: PointerEvent): void => {
    const previousVariant: ThemeVariant = isDarkTheme.value ? 'dark' : 'light';
    const variant: ThemeVariant = isDarkTheme.value ? 'light' : 'dark';

    theme.setTransitionOrigin(event);
    void theme.change(variant, true);

    router.put(page.props.theme.updateUrl, { variant }, {
        only: ['theme'],
        preserveScroll: true,
        preserveState: true,
        onError: () => {
            void theme.change(previousVariant, true);
        },
    });
};
</script>

<template>
    <v-btn
        :aria-label="themeSwitcherLabel"
        :icon="themeSwitcherIcon"
        :title="themeSwitcherLabel"
        @click="switchTheme"
    />

    <v-menu location="bottom end">
        <template #activator="{ props: activatorProps }">
            <v-btn v-bind="activatorProps" icon aria-label="Open user menu">
                <v-avatar color="primary" size="36">
                    <span class="text-body-2">{{ userInitial }}</span>
                </v-avatar>
            </v-btn>
        </template>

        <v-card min-width="240">
            <v-list>
                <v-list-item
                    :subtitle="user?.email ?? 'Not signed in'"
                    :title="user?.name ?? 'Guest'"
                    prepend-icon="bi-person-circle"
                />
            </v-list>

            <v-divider />

            <v-list density="compact" nav>
                <v-list-item prepend-icon="bi-gear" title="Settings" disabled />
                <v-list-item prepend-icon="bi-box-arrow-right" title="Sign out" disabled />
            </v-list>
        </v-card>
    </v-menu>
</template>
