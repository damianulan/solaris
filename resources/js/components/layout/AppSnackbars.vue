<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';
import type { SnackbarQueueMessage } from 'vuetify';

const page = usePage();
const queue = ref<SnackbarQueueMessage[]>([]);

const enqueue = () => {
    queue.value.push(...page.props.snackbars.map(({ message, color }) => ({ text: message, color })));
};

onMounted(enqueue);
watch(() => page.props.snackbars, enqueue);
</script>

<template>
    <v-snackbar-queue
        v-model="queue"
        closable
        timeout="5000"
        :total-visible="5"
        location="top end"
        variant="tonal"
    >
        <template #actions="{ props }">
            <v-btn
                v-bind="props"
                aria-label="Dismiss"
                icon="bi-x-lg"
                size="small"
                title="Dismiss"
                variant="text"
            />
        </template>
    </v-snackbar-queue>
</template>
