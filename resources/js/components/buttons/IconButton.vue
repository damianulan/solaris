<script setup lang="ts">
type ButtonType = 'button' | 'reset' | 'submit';
type ButtonVariant = 'elevated' | 'flat' | 'outlined' | 'plain' | 'text' | 'tonal';

withDefaults(defineProps<{
    appendIcon?: string;
    color?: string;
    disabled?: boolean;
    icon?: string;
    loading?: boolean;
    size: string;
    type?: ButtonType;
    variant?: ButtonVariant;
}>(), {
    appendIcon: undefined,
    color: 'primary',
    disabled: false,
    icon: undefined,
    loading: false,
    size: '1rem',
    prependIcon: undefined,
    type: 'button',
    variant: 'flat',
});
</script>

<template>
    <v-icon-btn
        :color="color"
        :disabled="disabled"
        :icon="icon"
        :loading="loading"
        :type="type"
        :variant="variant"
    >

        <slot />

        <template v-if="$slots.loader" #loader>
            <slot name="loader" />
        </template>
    </v-icon-btn>
</template>
