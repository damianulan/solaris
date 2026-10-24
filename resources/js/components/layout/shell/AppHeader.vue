<script setup lang="ts">
import { useTranslations } from '../../../lib/translations';
import AppBrand from '../branding/AppBrand.vue';
import AppTopbar from './AppTopbar.vue';

withDefaults(defineProps<{
    showNavigationToggle?: boolean;
}>(), {
    showNavigationToggle: true,
});

const { translate } = useTranslations();

const emit = defineEmits<{
    toggleNavigation: [];
}>();
</script>

<template>
    <v-app-bar
        border="b"
        class="px-1 px-sm-3"
        color="topbar"
        elevation="0"
    >
        <v-app-bar-nav-icon
            v-if="showNavigationToggle"
            :aria-label="translate('frontend.navigation.toggle')"
            color="brand"
            icon="bi-list"
            variant="text"
            @click="emit('toggleNavigation')"
        />

        <v-app-bar-title class="ms-2 ms-sm-3">
            <AppBrand />
        </v-app-bar-title>
        <AppTopbar />
    </v-app-bar>
</template>
