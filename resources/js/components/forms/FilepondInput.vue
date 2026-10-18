<script setup lang="ts">
import {
    create,
    registerPlugin,
    type FilePond,
    type FilePondErrorDescription,
    type FilePondFile,
} from 'filepond';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';

import { useTranslations } from '../../lib/translations';

import 'filepond/dist/filepond.min.css';

registerPlugin(FilePondPluginFileValidateSize, FilePondPluginFileValidateType);

const props = defineProps<{
    acceptedFileTypes: readonly string[];
    clear: boolean;
    errorMessages: string[];
    hint?: string;
    label: string;
    maxFileSize: string;
    modelValue: string | null;
    name: string;
    serverUrl: string;
}>();

const emit = defineEmits<{
    'update:modelValue': [value: string | null];
}>();

const { translate } = useTranslations();
const input = ref<HTMLInputElement | null>(null);
const isFocused = ref(false);
let pond: FilePond | null = null;

function csrfToken(): string {
    return document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? '';
}

function processed(error: FilePondErrorDescription | null, file: FilePondFile): void {
    if (error !== null) {
        emit('update:modelValue', null);

        return;
    }

    emit('update:modelValue', file.serverId);
}

function removed(): void {
    emit('update:modelValue', null);
}

function blurred(event: FocusEvent): void {
    if (event.currentTarget instanceof HTMLElement
        && event.relatedTarget instanceof Node
        && event.currentTarget.contains(event.relatedTarget)) {
        return;
    }

    isFocused.value = false;
}

onMounted(() => {
    if (input.value === null) {
        return;
    }

    pond = create(input.value, {
        acceptedFileTypes: [...props.acceptedFileTypes],
        allowMultiple: false,
        credits: false,
        fileValidateTypeLabelExpectedTypes: translate('frontend.profile.filepond.expected_types'),
        labelButtonAbortItemProcessing: translate('frontend.profile.filepond.cancel'),
        labelButtonProcessItem: translate('frontend.profile.filepond.upload'),
        labelButtonRemoveItem: translate('frontend.profile.filepond.remove'),
        labelButtonRetryItemProcessing: translate('frontend.profile.filepond.retry'),
        labelButtonUndoItemProcessing: translate('frontend.profile.filepond.undo'),
        labelFileProcessing: translate('frontend.profile.filepond.uploading'),
        labelFileProcessingComplete: translate('frontend.profile.filepond.upload_complete'),
        labelFileProcessingError: translate('frontend.profile.filepond.upload_error'),
        labelFileTypeNotAllowed: translate('frontend.profile.filepond.invalid_type'),
        labelIdle: translate('frontend.profile.filepond.idle'),
        labelMaxFileSize: translate('frontend.profile.filepond.max_size'),
        labelMaxFileSizeExceeded: translate('frontend.profile.filepond.too_large'),
        labelTapToCancel: translate('frontend.profile.filepond.cancel'),
        labelTapToRetry: translate('frontend.profile.filepond.retry'),
        labelTapToUndo: translate('frontend.profile.filepond.undo'),
        maxFileSize: props.maxFileSize,
        server: {
            url: props.serverUrl,
            headers: {
                'X-CSRF-TOKEN': csrfToken(),
            },
        },
        onprocessfile: processed,
        onremovefile: removed,
    });
});

watch(() => props.clear, (shouldClear) => {
    if (! shouldClear || pond === null) {
        return;
    }

    pond.removeFiles({ revert: false });
    emit('update:modelValue', null);
});

watch(() => props.modelValue, (value) => {
    if (value !== null || pond === null || pond.getFiles().length === 0) {
        return;
    }

    pond.removeFiles();
});

onBeforeUnmount(() => {
    pond?.destroy();
});
</script>

<template>
    <v-input
        color="primary"
        :error-messages="errorMessages"
        :focused="isFocused"
        :hint="hint"
        :id="name"
        indent-details
        :model-value="modelValue"
        :name="name"
        :persistent-hint="Boolean(hint)"
        class="filepond-input"
        @focusin="isFocused = true"
        @focusout="blurred"
    >
        <template #default>
            <v-field
                :id="name"
                active
                color="primary"
                :dirty="modelValue !== null"
                :error="errorMessages.length > 0"
                :focused="isFocused"
                :label="label"
                variant="outlined"
            >
                <template #default="{ props: fieldProps }">
                    <div :class="fieldProps.class">
                        <input
                            :id="name"
                            ref="input"
                            :aria-describedby="`${name}-messages`"
                            :name="name"
                            type="file"
                        >
                    </div>
                </template>
            </v-field>
        </template>
    </v-input>
</template>

<style scoped>
.filepond-input :deep(.v-field__input) {
    display: block;
    min-height: 92px;
    padding: 8px;
}

.filepond-input :deep(.filepond--root) {
    direction: inherit;
    font-family: inherit;
    margin: 0;
    min-height: 76px;
    width: 100%;
}

.filepond-input :deep(.filepond--panel-root) {
    background-color: rgba(var(--v-theme-on-surface), 0.04);
    border-radius: 2px;
}

.filepond-input :deep(.filepond--drop-label) {
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}

.filepond-input :deep(.filepond--label-action) {
    color: rgb(var(--v-theme-primary));
    text-decoration-color: currentColor;
}

.filepond-input :deep(.filepond--item-panel) {
    background-color: rgb(var(--v-theme-primary));
    border-radius: 2px;
}

.filepond-input :deep([data-filepond-item-state='processing-complete'] .filepond--item-panel) {
    background-color: rgb(var(--v-theme-success));
}

.filepond-input :deep([data-filepond-item-state*='error'] .filepond--item-panel),
.filepond-input :deep([data-filepond-item-state*='invalid'] .filepond--item-panel) {
    background-color: rgb(var(--v-theme-error));
}
</style>
