<script setup lang="ts">
type ButtonType = 'button' | 'reset' | 'submit';
type ButtonVariant = 'elevated' | 'flat' | 'outlined' | 'plain' | 'text' | 'tonal';

defineOptions({
    inheritAttrs: false,
});

withDefaults(defineProps<{
    appendIcon?: string;
    ariaLabel?: string;
    color?: string;
    disabled?: boolean;
    icon?: string;
    loading?: boolean;
    size?: string;
    tooltip?: string;
    type?: ButtonType;
    variant?: ButtonVariant;
}>(), {
    appendIcon: undefined,
    ariaLabel: undefined,
    color: 'primary',
    disabled: false,
    icon: undefined,
    loading: false,
    size: '1rem',
    tooltip: undefined,
    prependIcon: undefined,
    type: 'button',
    variant: 'flat',
});
</script>

<template>
    <v-tooltip
        :disabled="!tooltip"
        location="top"
        :text="tooltip ?? ''"
    >
        <template #activator="{ props: tooltipProps }">
            <span v-bind="tooltipProps" class="d-inline-flex">
                <v-icon-btn
                    v-bind="$attrs"
                    :aria-label="ariaLabel"
                    :color="color"
                    :disabled="disabled"
                    :icon="icon"
                    :loading="loading"
                    :size="size"
                    :type="type"
                    :variant="variant"
                >
                    <template v-if="$slots.default" #default>
                        <slot />
                    </template>

                    <template v-if="$slots.loader" #loader>
                        <slot name="loader" />
                    </template>
                </v-icon-btn>
            </span>
        </template>
    </v-tooltip>
</template>
