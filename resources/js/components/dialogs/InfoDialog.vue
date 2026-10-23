<script setup lang="ts">
import Button from '../buttons/Button.vue';

type ButtonVariant = 'elevated' | 'flat' | 'outlined' | 'plain' | 'text' | 'tonal';

withDefaults(defineProps<{
    color?: string;
    explanation?: string;
    icon?: string;
    maxWidth?: number | string;
    message: string;
    modelValue: boolean;
    okIcon?: string;
    okLabel?: string;
    okVariant?: ButtonVariant;
    persistent?: boolean;
    title: string;
}>(), {
    color: 'info',
    explanation: undefined,
    icon: 'bi-info-circle',
    maxWidth: 480,
    okIcon: 'bi-check-lg',
    okLabel: 'OK',
    okVariant: 'outlined',
    persistent: true,
});

const emit = defineEmits<{
    close: [];
    'update:modelValue': [value: boolean];
}>();

function close(): void {
    emit('close');
    emit('update:modelValue', false);
}
</script>

<template>
    <v-dialog
        :model-value="modelValue"
        :max-width="maxWidth"
        :persistent="persistent"
        @update:model-value="emit('update:modelValue', $event)"
    >
        <v-card border elevation="8" :rounded="false">
            <v-card-item class="px-5 pb-2 pt-5">
                <template v-if="icon" #prepend>
                    <v-avatar :color="color" size="44" variant="tonal">
                        <v-icon :icon="icon" size="22" />
                    </v-avatar>
                </template>

                <v-card-title class="font-weight-medium text-title-large">
                    {{ title }}
                </v-card-title>
            </v-card-item>

            <v-card-text class="info-dialog__message px-5 pb-5 pt-2 text-body-large text-small-emphasis">
                {{ message }}
            </v-card-text>

            <v-card-text v-if="explanation" class="info-dialog__message px-5 pb-5 pt-0 text-body-small text-medium-emphasis">
                {{ explanation }}
            </v-card-text>

            <v-divider />

            <v-card-actions class="px-5 py-4">
                <v-spacer />

                <Button
                    :color="color"
                    :prepend-icon="okIcon"
                    :variant="okVariant"
                    @click="close"
                >
                    {{ okLabel }}
                </Button>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<style scoped>
.info-dialog__message {
    line-height: 1.5;
}
</style>
