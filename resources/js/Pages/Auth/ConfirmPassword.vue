<script setup lang="ts">
import { Head, router, useHttp } from '@inertiajs/vue3';
import { ref } from 'vue';

import AuthShell from '../../components/auth/AuthShell.vue';
import SubmitButton from '../../components/buttons/SubmitButton.vue';
import { useTranslations } from '../../lib/translations';

const props = defineProps<{
    confirmPasswordUrl: string;
    homeUrl: string;
}>();

const isPasswordVisible = ref(false);
const submissionError = ref<string | null>(null);
const { translate } = useTranslations();
const form = useHttp('ConfirmPasswordForm', {
    password: '',
}).dontRemember('password');

function passwordErrors(): string[] {
    const error = form.errors.password;

    if (Array.isArray(error)) {
        return error;
    }

    return error ? [error] : [];
}

function submit(): void {
    submissionError.value = null;

    void form.post(props.confirmPasswordUrl, {
        onSuccess: () => router.visit(props.homeUrl),
        onHttpException: () => {
            submissionError.value = translate('frontend.auth.confirm_password.submission_error');
        },
        onNetworkError: () => {
            submissionError.value = translate('frontend.auth.common.network_error');
        },
    });
}
</script>

<template>
    <AuthShell
        :description="translate('frontend.auth.confirm_password.description')"
        :eyebrow="translate('frontend.auth.confirm_password.eyebrow')"
        :title="translate('frontend.auth.confirm_password.title')"
    >
        <Head :title="translate('frontend.auth.confirm_password.head_title')" />

        <v-alert
            v-if="submissionError"
            class="mb-5"
            closable
            type="error"
            variant="tonal"
            @click:close="submissionError = null"
        >
            {{ submissionError }}
        </v-alert>

        <form novalidate @submit.prevent="submit">
            <v-text-field
                v-model="form.password"
                autocomplete="current-password"
                :append-inner-icon="isPasswordVisible ? 'bi-eye-slash' : 'bi-eye'"
                :error-messages="passwordErrors()"
                :label="translate('frontend.auth.common.password')"
                name="password"
                prepend-inner-icon="bi-lock"
                :type="isPasswordVisible ? 'text' : 'password'"
                variant="outlined"
                @click:append-inner="isPasswordVisible = !isPasswordVisible"
            />

            <SubmitButton
                class="auth-submit mt-3"
                :loading="form.processing"
                prepend-icon="bi-shield-check"
            >
                {{ translate('frontend.auth.confirm_password.submit') }}
            </SubmitButton>
        </form>
    </AuthShell>
</template>
