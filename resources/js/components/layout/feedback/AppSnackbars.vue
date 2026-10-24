<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';
import type { SnackbarQueueMessage } from 'vuetify';

const page = usePage();
const queue = ref<SnackbarQueueMessage[]>([]);

const enqueue = () => {
    queue.value.push(...page.props.snackbars.map(({ text, color, icon, closeable }) => ({ text, color, prependIcon: icon, closeable })));
};

const snackbarText = (item: SnackbarQueueMessage): string => typeof item === 'string' ? item : item.text ?? '';

onMounted(enqueue);
watch(() => page.props.snackbars, enqueue);
</script>

<template>
    <v-snackbar-queue
        v-model="queue"
        contained
        :content-props="{ style: { border: '3px solid currentColor' } }"
        eager
        :max-width="500"
        timeout="100000"
        :total-visible="10"
        :rounded="false"
        location="top end"
        variant="tonal"
    >
        <template #text="{ item }">
            <span class="font-weight-bold">{{ snackbarText(item) }}</span>
        </template>

        <template #actions="{ props: actionProps }">
            <v-tooltip location="start" text="Dismiss">
                <template #activator="{ props: tooltipProps }">
                    <v-btn
                        v-bind="{ ...actionProps, ...tooltipProps }"
                        aria-label="Dismiss"
                        icon="bi-x-lg"
                        size="small"
                        variant="text"
                    />
                </template>
            </v-tooltip>
        </template>
    </v-snackbar-queue>
</template>
