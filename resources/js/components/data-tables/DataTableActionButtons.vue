<script setup lang="ts">
import { computed } from 'vue';

import type { DataTableActionButton } from '../../lib/types';
import IconButton from '../buttons/IconButton.vue';

const props = defineProps<{
    value: unknown;
}>();

const emit = defineEmits<{
    action: [button: DataTableActionButton];
}>();

const buttons = computed<DataTableActionButton[]>(() => {
    if (!Array.isArray(props.value)) {
        return [];
    }

    return props.value.filter((button): button is DataTableActionButton => (
        typeof button === 'object'
        && button !== null
        && typeof Reflect.get(button, 'href') === 'string'
    ));
});
</script>

<template>
    <div class="d-flex justify-end ga-1">
        <IconButton
            v-for="(button, index) in buttons"
            :key="`${button.method ?? 'get'}-${button.href}-${index}`"
            :aria-label="button.title"
            :color="button.color"
            :disabled="button.disabled"
            :icon="button.icon"
            size="small"
            :title="button.title"
            :variant="button.variant"
            @click="emit('action', button)"
        />
    </div>
</template>
