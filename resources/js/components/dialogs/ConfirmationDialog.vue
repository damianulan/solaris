<script setup lang="ts">
import Button from '../buttons/Button.vue';

type ButtonVariant = 'elevated' | 'flat' | 'outlined' | 'plain' | 'text' | 'tonal';

withDefaults(defineProps<{
    cancelColor?: string;
    cancelIcon?: string;
    cancelLabel: string;
    cancelVariant?: ButtonVariant;
    color?: string;
    confirmIcon?: string;
    confirmLabel: string;
    confirmVariant?: ButtonVariant;
    icon?: string;
    maxWidth?: number | string;
    message: string;
    modelValue: boolean;
    persistent?: boolean;
    title: string;
    explaination?: string;
}>(), {
    cancelColor: 'error',
    cancelIcon: 'bi-x-lg',
    cancelVariant: 'outlined',
    color: 'success',
    confirmIcon: 'bi-check-lg',
    confirmVariant: 'outlined',
    icon: 'bi-question-circle',
    maxWidth: 480,
    persistent: true,
    explaination: undefined,
});

const emit = defineEmits<{
    confirm: [];
    'update:modelValue': [value: boolean];
}>();

function cancel(): void {
    emit('update:modelValue', false);
}

function confirm(): void {
    emit('confirm');
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

            <v-card-text class="confirmation-dialog__message px-5 pb-5 pt-2 text-body-large text-small-emphasis">
                {{ message }}
            </v-card-text>

            <v-card-text v-if="explaination" class="confirmation-dialog__message px-5 pb-5 pt-0 text-body-small text-medium-emphasis">
                {{ explaination }}
            </v-card-text>

            <v-divider />

            <v-card-actions class="ga-2 px-5 py-4">
                <v-spacer />

                <Button
                    :color="cancelColor"
                    :prepend-icon="cancelIcon"
                    :variant="cancelVariant"
                    @click="cancel"
                >
                    {{ cancelLabel }}
                </Button>

                <Button
                    :color="color"
                    :prepend-icon="confirmIcon"
                    :variant="confirmVariant"
                    @click="confirm"
                >
                    {{ confirmLabel }}
                </Button>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<style scoped>
.confirmation-dialog__message {
    line-height: 1.5;
}
</style>
