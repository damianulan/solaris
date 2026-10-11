<script setup lang="ts">
import { Head, Link, useHttp } from '@inertiajs/vue3';
import { ref } from 'vue';

import AuthShell from '../../components/auth/AuthShell.vue';
import SubmitButton from '../../components/buttons/SubmitButton.vue';
import { useTranslations } from '../../lib/translations';

const props = defineProps<{
    loginUrl: string;
    passwordEmailUrl: string;
}>();

const linkSent = ref(false);
const submissionError = ref<string | null>(null);
const { translate } = useTranslations();
const form = useHttp('ForgotPasswordForm', {
    email: '',
});

function emailErrors(): string[] {
    const error = form.errors.email;

    if (Array.isArray(error)) {
        return error;
    }

    return error ? [error] : [];
}

function submit(): void {
    submissionError.value = null;

    void form.post(props.passwordEmailUrl, {
        onSuccess: () => {
            linkSent.value = true;
        },
        onHttpException: () => {
            submissionError.value = translate('frontend.auth.forgot_password.submission_error');
        },
        onNetworkError: () => {
            submissionError.value = translate('frontend.auth.common.network_error');
        },
    });
}
</script>

<template>
    <AuthShell
        :description="translate('frontend.auth.forgot_password.description')"
        :eyebrow="translate('frontend.auth.forgot_password.eyebrow')"
        :title="translate('frontend.auth.forgot_password.title')"
    >
        <Head :title="translate('frontend.auth.forgot_password.head_title')" />

        <v-alert v-if="linkSent" class="mb-5" type="success" variant="tonal">
            {{ translate('frontend.auth.forgot_password.sent') }}
        </v-alert>

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
                :error-messages="emailErrors()"
                :label="translate('frontend.auth.common.email')"
                name="email"
                :placeholder="translate('frontend.auth.common.email_placeholder')"
                prepend-inner-icon="bi-envelope"
                type="email"
                variant="outlined"
            />

            <SubmitButton
                class="auth-submit mt-3"
                :loading="form.processing"
                prepend-icon="bi-send"
            >
                {{ translate('frontend.auth.forgot_password.submit') }}
            </SubmitButton>
        </form>

        <p class="auth-footer">
            {{ translate('frontend.auth.forgot_password.remembered') }}
            <Link class="auth-link" :href="loginUrl">
                {{ translate('frontend.auth.common.back_to_sign_in') }}
            </Link>
        </p>
    </AuthShell>
</template>
