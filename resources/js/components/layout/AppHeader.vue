<script setup lang="ts">
import { useTranslations } from '../../lib/translations';
import AppTopbar from './AppTopbar.vue';

const solarisLogoUrl = '/images/solaris-logo.svg';
const { translate } = useTranslations();

const emit = defineEmits<{
    toggleNavigation: [];
}>();
</script>

<template>
    <v-app-bar elevation="1">
        <v-app-bar-nav-icon
            :aria-label="translate('frontend.navigation.toggle')"
            icon="bi-list"
            @click="emit('toggleNavigation')"
        />
        <img :alt="translate('frontend.visual.logo_alt')" class="app-bar-logo" :src="solarisLogoUrl" />
        <v-app-bar-title>{{ translate('frontend.app.name') }}</v-app-bar-title>
        <AppTopbar />
    </v-app-bar>
</template>

<style scoped>
.app-bar-logo {
    height: 32px;
    margin-inline-start: 8px;
    width: 37px;
    color: var(--v-theme-primary);
}
</style>
