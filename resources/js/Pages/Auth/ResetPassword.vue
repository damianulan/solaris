<script setup lang="ts">
import { Head, Link, router, useHttp } from '@inertiajs/vue3';
import { ref } from 'vue';

import AuthShell from '../../components/auth/AuthShell.vue';
import SubmitButton from '../../components/buttons/SubmitButton.vue';
import { useTranslations } from '../../lib/translations';

type ResetField = 'email' | 'password' | 'password_confirmation';

const props = defineProps<{
    email: string;
    loginUrl: string;
    resetPasswordUrl: string;
    token: string;
}>();

const isPasswordVisible = ref(false);
const submissionError = ref<string | null>(null);
const { translate } = useTranslations();
const form = useHttp('ResetPasswordForm', {
    email: props.email,
    password: '',
    password_confirmation: '',
    token: props.token,
}).dontRemember('password', 'password_confirmation', 'token');

function errorMessages(field: ResetField): string[] {
    const error = form.errors[field];

    if (Array.isArray(error)) {
        return error;
    }

    return error ? [error] : [];
}

function submit(): void {
    submissionError.value = null;

    void form.post(props.resetPasswordUrl, {
        onSuccess: () => router.visit(props.loginUrl),
        onHttpException: () => {
            submissionError.value = translate('frontend.auth.reset_password.submission_error');
        },
        onNetworkError: () => {
            submissionError.value = translate('frontend.auth.common.network_error');
        },
    });
}
</script>

<template>
    <AuthShell
        :description="translate('frontend.auth.reset_password.description')"
        :eyebrow="translate('frontend.auth.reset_password.eyebrow')"
        :title="translate('frontend.auth.reset_password.title')"
    >
        <Head :title="translate('frontend.auth.reset_password.head_title')" />

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
                v-model="form.email"
                autocomplete="email"
                :error-messages="errorMessages('email')"
                :label="translate('frontend.auth.common.email')"
                name="email"
                prepend-inner-icon="bi-envelope"
                type="email"
                variant="outlined"
            />

            <v-text-field
                v-model="form.password"
                autocomplete="new-password"
                :append-inner-icon="isPasswordVisible ? 'bi-eye-slash' : 'bi-eye'"
                :error-messages="errorMessages('password')"
                :hint="translate('frontend.auth.common.password_hint')"
                :label="translate('frontend.auth.reset_password.new_password')"
                name="password"
                prepend-inner-icon="bi-lock"
                :type="isPasswordVisible ? 'text' : 'password'"
                variant="outlined"
                @click:append-inner="isPasswordVisible = !isPasswordVisible"
            />

            <v-text-field
                v-model="form.password_confirmation"
                autocomplete="new-password"
                :error-messages="errorMessages('password_confirmation')"
                :label="translate('frontend.auth.reset_password.confirm_password')"
                name="password_confirmation"
                prepend-inner-icon="bi-lock-fill"
                :type="isPasswordVisible ? 'text' : 'password'"
                variant="outlined"
            />

            <SubmitButton
                class="auth-submit mt-3"
                :loading="form.processing"
                prepend-icon="bi-check-circle"
            >
                {{ translate('frontend.auth.reset_password.submit') }}
            </SubmitButton>
        </form>

        <p class="auth-footer">
            <Link class="auth-link" :href="loginUrl">
                {{ translate('frontend.auth.common.back_to_sign_in') }}
            </Link>
        </p>
    </AuthShell>
</template>
