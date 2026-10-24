<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <div v-if="page.props.alerts.length" class="mb-4 d-flex flex-column ga-2">
        <v-alert
            v-for="(alert, index) in page.props.alerts"
            :key="`${alert.text}-${index}`"
            :closable="alert.closeable"
            :color="alert.color"
            :icon="alert.icon ?? undefined"
            icon-size="20"
            prominent
            :text="alert.text"
            :rounded="false"
            close-icon="bi-x-lg"
            style="background-color: color-mix(in srgb, currentColor 8%, transparent); border-width: 3px;"
            variant="outlined"
        >
            <template v-if="alert.header" #title>
                <span class="font-weight-bold text-title-large">{{ alert.header }}</span>
            </template>

            <template #text>
                <span class="font-weight-bold text-body-medium">{{ alert.text }}</span>
            </template>
        </v-alert>
    </div>
</template>
