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
    <v-app-bar
        border="b"
        class="px-1 px-sm-3"
        color="surface"
        elevation="0"
    >
        <v-app-bar-nav-icon
            :aria-label="translate('frontend.navigation.toggle')"
            color="primary"
            icon="bi-list"
            variant="tonal"
            @click="emit('toggleNavigation')"
        />

        <v-app-bar-title class="ms-2 ms-sm-3">
            <AppBrand />
        </v-app-bar-title>
        <AppTopbar />
    </v-app-bar>
</template>
