<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import { useTranslations } from '../../../lib/translations';

const localeHeader = 'X-Locale';
const page = usePage();
const { translate } = useTranslations();
const isSwitching = ref(false);

const currentLocale = computed(() => page.props.localization.locale);

function switchLocale(locale: string): void {
    if (locale === currentLocale.value || isSwitching.value) {
        return;
    }

    isSwitching.value = true;

    router.reload({
        headers: {
            [localeHeader]: locale,
        },
        onFinish: () => {
            isSwitching.value = false;
        },
    });
}
</script>

<template>
    <v-menu location="bottom end">
        <template #activator="{ props: activatorProps }">
            <v-btn
                v-bind="activatorProps"
                :aria-label="translate('frontend.locale.open_menu')"
                :loading="isSwitching"
                prepend-icon="bi-translate"
                variant="text"
            >
                {{ currentLocale.toUpperCase() }}
            </v-btn>
        </template>

        <v-list density="compact" min-width="180">
            <v-list-item
                v-for="locale in page.props.availableLocales"
                :key="locale"
                :active="locale === currentLocale"
                :append-icon="locale === currentLocale ? 'bi-check' : undefined"
                :disabled="isSwitching"
                :title="translate(`frontend.profile.languages.${locale}`)"
                @click="switchLocale(locale)"
            />
        </v-list>
    </v-menu>
</template>
