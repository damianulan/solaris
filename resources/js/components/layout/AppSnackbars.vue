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
        eager
        timeout="8000"
        :total-visible="5"
        :max-width="500"
        location="top end"
        variant="flat"
    >
        <template #text="{ item }">
            <span class="font-weight-bold">{{ snackbarText(item) }}</span>
        </template>

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
