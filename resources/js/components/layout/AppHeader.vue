<script setup lang="ts">
import AppTopbar from './AppTopbar.vue';

const emit = defineEmits<{
    toggleNavigation: [];
}>();
</script>

<template>
    <v-app-bar elevation="1">
        <v-app-bar-nav-icon icon="bi-list" @click="emit('toggleNavigation')" />
        <v-app-bar-title>Solaris</v-app-bar-title>
        <AppTopbar />
    </v-app-bar>
</template>
