<script setup lang="ts">
import { Head, Link, router, useHttp } from '@inertiajs/vue3';
import { ref } from 'vue';

import AuthShell from '../../components/auth/AuthShell.vue';
import SubmitButton from '../../components/buttons/SubmitButton.vue';
import TextInput from '../../components/forms/TextInput.vue';
import { useTranslations } from '../../lib/translations';

type RegistrationField =
    | 'email'
    | 'firstname'
    | 'lastname'
    | 'middlename'
    | 'password'
    | 'password_confirmation'
    | 'username';

const props = defineProps<{
    homeUrl: string;
    loginUrl: string;
    registerUrl: string;
}>();

const isPasswordVisible = ref(false);
const submissionError = ref<string | null>(null);
const { translate } = useTranslations();
const form = useHttp('RegisterForm', {
    firstname: '',
    lastname: '',
    middlename: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
}).dontRemember('password', 'password_confirmation');

function errorMessages(field: RegistrationField): string[] {
    const error = form.errors[field];

    if (Array.isArray(error)) {
        return error;
    }

    return error ? [error] : [];
}

function submit(): void {
    submissionError.value = null;

    void form.post(props.registerUrl, {
        onSuccess: () => router.visit(props.homeUrl),
        onHttpException: () => {
            submissionError.value = translate('frontend.auth.register.submission_error');
        },
        onNetworkError: () => {
            submissionError.value = translate('frontend.auth.common.network_error');
        },
    });
}
</script>

<template>
    <AuthShell
        :description="translate('frontend.auth.register.description')"
        :eyebrow="translate('frontend.auth.register.eyebrow')"
        :title="translate('frontend.auth.register.title')"
    >
        <Head :title="translate('frontend.auth.register.head_title')" />

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
            <div class="name-fields">
                <TextInput
                    v-model="form.firstname"
                    autocomplete="given-name"
                    :error-messages="errorMessages('firstname')"
                    :label="translate('frontend.auth.register.first_name')"
                    name="firstname"
                />
                <TextInput
                    v-model="form.lastname"
                    autocomplete="family-name"
                    :error-messages="errorMessages('lastname')"
                    :label="translate('frontend.auth.register.last_name')"
                    name="lastname"
                />
            </div>

            <TextInput
                v-model="form.middlename"
                autocomplete="additional-name"
                :error-messages="errorMessages('middlename')"
                :label="translate('frontend.auth.register.middle_name')"
                name="middlename"
            />

            <TextInput
                v-model="form.username"
                autocomplete="username"
                :error-messages="errorMessages('username')"
                :label="translate('frontend.auth.register.username')"
                name="username"
                prepend-inner-icon="bi-person"
            />

            <TextInput
                v-model="form.email"
                autocomplete="email"
                :error-messages="errorMessages('email')"
                :label="translate('frontend.auth.common.email')"
                name="email"
                prepend-inner-icon="bi-envelope"
                type="email"
            />

            <TextInput
                v-model="form.password"
                autocomplete="new-password"
                :append-inner-icon="isPasswordVisible ? 'bi-eye-slash' : 'bi-eye'"
                :error-messages="errorMessages('password')"
                :hint="translate('frontend.auth.common.password_hint')"
                :label="translate('frontend.auth.common.password')"
                name="password"
                prepend-inner-icon="bi-lock"
                :type="isPasswordVisible ? 'text' : 'password'"
                @click:append-inner="isPasswordVisible = !isPasswordVisible"
            />

            <TextInput
                v-model="form.password_confirmation"
                autocomplete="new-password"
                :error-messages="errorMessages('password_confirmation')"
                :label="translate('frontend.auth.register.confirm_password')"
                name="password_confirmation"
                prepend-inner-icon="bi-lock-fill"
                :type="isPasswordVisible ? 'text' : 'password'"
            />

            <SubmitButton
                class="auth-submit mt-3"
                :loading="form.processing"
                prepend-icon="bi-person-plus"
            >
                {{ translate('frontend.auth.register.submit') }}
            </SubmitButton>
        </form>

        <p class="auth-footer">
            {{ translate('frontend.auth.register.has_account') }}
            <Link class="auth-link" :href="loginUrl">
                {{ translate('frontend.auth.register.sign_in') }}
            </Link>
        </p>
    </AuthShell>
</template>

<style scoped>
.name-fields {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
}

@media (max-width: 520px) {
    .name-fields {
        display: block;
    }
}
</style>
