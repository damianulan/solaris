<script setup lang="ts">
import AppTopbar from './AppTopbar.vue';

const solarisLogoUrl = '/images/solaris-logo.svg';

const emit = defineEmits<{
    toggleNavigation: [];
}>();
</script>

<template>
    <v-app-bar elevation="1">
        <v-app-bar-nav-icon icon="bi-list" @click="emit('toggleNavigation')" />
        <img alt="Solaris logo" class="app-bar-logo" :src="solarisLogoUrl" />
        <v-app-bar-title>Solaris</v-app-bar-title>
        <AppTopbar />
    </v-app-bar>
</template>

<style scoped>
.app-bar-logo {
    height: 32px;
    margin-inline-start: 8px;
    width: 37px;
}
</style>
