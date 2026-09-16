<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AppLayout from '../components/layout/AppLayout.vue';
</script>

<template>
    <Head title="Welcome" />

    <AppLayout>
        <v-card max-width="640" class="mx-auto" elevation="2">
            <v-card-title class="text-h4 pt-6">Solaris is ready</v-card-title>
            <v-card-text class="text-body-1">
                Laravel, Inertia, Vue, Vuetify, TypeScript, and Vite are configured.
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
