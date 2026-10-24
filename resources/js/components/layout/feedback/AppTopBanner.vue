<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(defineProps<{
    backgroundColor?: string;
    prependIcon?: string;
    text?: string | null;
}>(), {
    backgroundColor: 'error',
    prependIcon: undefined,
    text: null,
});

const hasText = computed(() => Boolean(props.text?.trim()));
</script>

<template>
    <v-system-bar
        v-if="hasText"
        class="pa-0"
        color="surface"
        height="40"
        order="-1"
    >
        <v-alert
            :color="props.backgroundColor"
            class="font-weight-bold justify-center text-center text-uppercase"
            density="compact"
            height="100%"
            :icon="props.prependIcon || false"
            rounded="0"
            :text="props.text ?? undefined"
            variant="tonal"
            width="100%"
        />
    </v-system-bar>
</template>
