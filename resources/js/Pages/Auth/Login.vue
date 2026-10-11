<script setup lang="ts">
import { Head, Link, router, useHttp } from '@inertiajs/vue3';
import { ref } from 'vue';

import AuthShell from '../../components/auth/AuthShell.vue';
import SubmitButton from '../../components/buttons/SubmitButton.vue';
import { useTranslations } from '../../lib/translations';
import AppAlerts from '@/components/layout/AppAlerts.vue';

const props = defineProps<{
    homeUrl: string;
    loginUrl: string;
    passwordRequestUrl: string;
    registerUrl: string;
}>();

const isPasswordVisible = ref(false);
const submissionError = ref<string | null>(null);
const { translate } = useTranslations();
const form = useHttp('LoginForm', {
    email: '',
    password: '',
    remember: false,
}).dontRemember('password');

function errorMessages(field: 'email' | 'password'): string[] {
    const error = form.errors[field];

    if (Array.isArray(error)) {
        return error;
    }

    return error ? [error] : [];
}

function submit(): void {
    submissionError.value = null;

    void form.post(props.loginUrl, {
        onSuccess: () => router.visit(props.homeUrl)
    });
}
</script>

<template>
    <AuthShell
        :description="translate('frontend.auth.login.description')"
        :eyebrow="translate('frontend.auth.login.eyebrow')"
        :title="translate('frontend.auth.login.title')"
    >
        <Head :title="translate('frontend.auth.login.head_title')" />
        <AppAlerts />

        <form novalidate @submit.prevent="submit">
            <v-text-field
                v-model="form.email"
                autocomplete="email"
                class="mb-2"
                :error-messages="errorMessages('email')"
                :label="translate('frontend.auth.common.email')"
                name="email"
                :placeholder="translate('frontend.auth.common.email_placeholder')"
                prepend-inner-icon="bi-envelope"
                type="email"
                variant="outlined"
            />

            <v-text-field
                v-model="form.password"
                autocomplete="current-password"
                :append-inner-icon="isPasswordVisible ? 'bi-eye-slash' : 'bi-eye'"
                :error-messages="errorMessages('password')"
                :label="translate('frontend.auth.common.password')"
                name="password"
                prepend-inner-icon="bi-lock"
                :type="isPasswordVisible ? 'text' : 'password'"
                variant="outlined"
                @click:append-inner="isPasswordVisible = !isPasswordVisible"
            />

            <div class="form-options">
                <v-checkbox
                    v-model="form.remember"
                    color="primary"
                    density="compact"
                    hide-details
                    :label="translate('frontend.auth.login.remember')"
                    name="remember"
                />
                <Link class="auth-link" :href="passwordRequestUrl">
                    {{ translate('frontend.auth.login.forgot_password') }}
                </Link>
            </div>

            <SubmitButton
                class="auth-submit"
                :loading="form.processing"
                prepend-icon="bi-arrow-right-circle"
            >
                {{ translate('frontend.auth.login.submit') }}
            </SubmitButton>
        </form>

        <p class="auth-footer">
            {{ translate('frontend.auth.login.new_user') }}
            <Link class="auth-link" :href="registerUrl">
                {{ translate('frontend.auth.login.create_account') }}
            </Link>
        </p>
    </AuthShell>
</template>

<style scoped>
.form-options {
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 3rem;
    margin: 0.15rem 0 1.4rem;
}

@media (max-width: 460px) {
    .form-options {
        align-items: flex-start;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
}
</style>
