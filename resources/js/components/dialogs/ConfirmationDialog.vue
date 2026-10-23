<script setup lang="ts">
import Button from '../buttons/Button.vue';

withDefaults(defineProps<{
    cancelLabel: string;
    color?: string;
    confirmLabel: string;
    message: string;
    modelValue: boolean;
    title: string;
}>(), {
    color: 'primary',
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
    <v-dialog :model-value="modelValue" max-width="480" @update:model-value="emit('update:modelValue', $event)">
        <v-card>
            <v-card-title>
                {{ title }}
            </v-card-title>

            <v-card-text>
                {{ message }}
            </v-card-text>

            <v-card-actions>
                <v-spacer />

                <Button variant="text" @click="cancel">
                    {{ cancelLabel }}
                </Button>

                <Button :color="color" @click="confirm">
                    {{ confirmLabel }}
                </Button>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
