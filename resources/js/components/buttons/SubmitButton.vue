<script setup lang="ts">

withDefaults(defineProps<{
    appendIcon?: string;
    color?: string;
    disabled?: boolean;
    loading?: boolean;
    prependIcon?: string;
}>(), {
    appendIcon: undefined,
    color: 'primary',
    disabled: false,
    loading: false,
    prependIcon: undefined,
});
</script>

<template>
    <v-btn
        class="app-button"
        :append-icon="appendIcon"
        :color="color"
        :disabled="disabled"
        :loading="loading"
        :prepend-icon="prependIcon"
        type="submit"
        variant="flat"
    >
        <template v-if="$slots.prepend" #prepend>
            <slot name="prepend" />
        </template>

        <slot />

        <template v-if="$slots.append" #append>
            <slot name="append" />
        </template>

        <template v-if="$slots.loader" #loader>
            <slot name="loader" />
        </template>
    </v-btn>
</template>

<style scoped>
.app-button {
    font-weight: 600;
    letter-spacing: normal;
    text-transform: none;
}
</style>
