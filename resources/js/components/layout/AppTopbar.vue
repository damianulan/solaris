<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useTheme } from 'vuetify';

import { useTranslations } from '../../lib/translations';
import type { ThemeVariant, UserContext } from '../../lib/types';
import AppUserMenu from './AppUserMenu.vue';

const props = defineProps<{
    user?: UserContext;
}>();

const theme = useTheme();
const page = usePage();
const { translate } = useTranslations();

const isDarkTheme = computed(() => theme.current.value.dark);
const themeSwitcherIcon = computed(() => isDarkTheme.value ? 'bi-sun' : 'bi-moon-stars');
const themeSwitcherLabel = computed(() => translate(
    isDarkTheme.value ? 'frontend.theme.switch_to_light' : 'frontend.theme.switch_to_dark',
));

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

    <AppUserMenu :user="props.user" />
</template>
