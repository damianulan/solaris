<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <div v-if="page.props.alerts.length" class="d-flex flex-column ga-3 mb-4">
        <v-alert
            v-for="(alert, index) in page.props.alerts"
            :key="`${alert.text}-${index}`"
            :closable="alert.closeable"
            :color="alert.color"
            :icon="alert.icon ?? undefined"
            icon-size="26"
            prominent
            :text="alert.text"
            close-icon="bi-x-lg"
            variant="tonal"
            border
        >
            <template v-if="alert.header" #title>
                <span class="font-weight-medium text-title-large">{{ alert.header }}</span>
            </template>

            <template #text>
                <span class="font-weight-medium text-body-medium">{{ alert.text }}</span>
            </template>
        </v-alert>
    </div>
</template>
