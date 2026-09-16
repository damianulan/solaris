<script setup lang="ts">
import { ref } from 'vue';

import AppFooter from './AppFooter.vue';
import AppHeader from './AppHeader.vue';
import AppNavigation from './AppNavigation.vue';

const isNavigationOpen = ref(true);
</script>

<template>
    <v-app>
        <AppHeader @toggle-navigation="isNavigationOpen = !isNavigationOpen" />
        <AppNavigation v-model:is-open="isNavigationOpen" />

        <v-main>
            <v-container class="py-8" fluid>
                <slot />
            </v-container>
        </v-main>

        <AppFooter />
    </v-app>
</template>
