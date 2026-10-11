<script setup lang="ts">
import { useTranslations } from '../../lib/translations';
import AppBrand from './AppBrand.vue';
import AppTopbar from './AppTopbar.vue';

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

        <v-app-bar-title>
            <AppBrand />
        </v-app-bar-title>
        <AppTopbar />
    </v-app-bar>
</template>

<style scoped>
.v-toolbar__content > .v-toolbar-title {
    margin-inline-start: 10px;
}
</style>
