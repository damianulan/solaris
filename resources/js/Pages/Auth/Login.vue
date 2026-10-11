<script setup lang="ts">
import { Head, router, useHttp } from '@inertiajs/vue3';
import { ref } from 'vue';

import SubmitButton from '../../components/buttons/SubmitButton.vue';

const props = defineProps<{
    homeUrl: string;
    loginUrl: string;
}>();

const isPasswordVisible = ref(false);
const submissionError = ref<string | null>(null);
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
        onSuccess: () => router.visit(props.homeUrl),
        onHttpException: () => {
            submissionError.value = 'We could not sign you in. Please try again.';
        },
        onNetworkError: () => {
            submissionError.value = 'The server could not be reached. Check your connection and try again.';
        },
    });
}
</script>

<template>
    <v-app>
        <Head title="Sign in" />

        <v-main class="login-page">
            <div class="login-shell">
                <section class="brand-panel" aria-labelledby="welcome-heading">
                    <div class="brand-mark" aria-hidden="true">
                        <span class="brand-mark__core" />
                    </div>

                    <div class="brand-copy">
                        <p class="brand-name">SOLARIS</p>
                        <h1 id="welcome-heading">Your work, in a brighter orbit.</h1>
                        <p>
                            A focused workspace for teams who want clarity, momentum,
                            and a single place to move their best ideas forward.
                        </p>
                    </div>

                    <div class="trust-note">
                        <i class="bi bi-shield-check" aria-hidden="true" />
                        <span>Secure access to your Solaris workspace</span>
                    </div>
                </section>

                <section class="form-panel" aria-labelledby="sign-in-heading">
                    <div class="form-wrap">
                        <div class="mobile-brand" aria-hidden="true">
                            <span class="mobile-brand__sun" />
                            <span>SOLARIS</span>
                        </div>

                        <div class="form-heading">
                            <p class="eyebrow">Welcome back</p>
                            <h2 id="sign-in-heading">Sign in to continue</h2>
                            <p>Enter your details to access your workspace.</p>
                        </div>

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
                                class="mb-2"
                                :error-messages="errorMessages('email')"
                                label="Email address"
                                name="email"
                                placeholder="you@company.com"
                                prepend-inner-icon="bi-envelope"
                                type="email"
                                variant="outlined"
                            />

                            <v-text-field
                                v-model="form.password"
                                autocomplete="current-password"
                                :append-inner-icon="isPasswordVisible ? 'bi-eye-slash' : 'bi-eye'"
                                :error-messages="errorMessages('password')"
                                label="Password"
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
                                    label="Keep me signed in"
                                    name="remember"
                                />
                                <span class="security-label">Protected session</span>
                            </div>

                            <SubmitButton
                                class="sign-in-button"
                                :loading="form.processing"
                                prepend-icon="bi-arrow-right-circle"
                            >
                                Sign in
                            </SubmitButton>
                        </form>

                        <p class="support-copy">
                            Need help accessing your account? Contact your workspace administrator.
                        </p>
                    </div>
                </section>
            </div>
        </v-main>
    </v-app>
</template>

<style scoped>
.login-page {
    min-height: 100vh;
    background:
        radial-gradient(circle at 82% 12%, rgba(var(--v-theme-primary), 0.12), transparent 32rem),
        rgb(var(--v-theme-surface));
}

.login-shell {
    display: grid;
    grid-template-columns: minmax(22rem, 0.95fr) minmax(28rem, 1.05fr);
    min-height: 100vh;
}

.brand-panel {
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow: hidden;
    padding: clamp(2.5rem, 6vw, 6rem);
    color: #fff;
    background:
        radial-gradient(circle at 82% 18%, rgba(255, 203, 111, 0.34), transparent 16rem),
        linear-gradient(145deg, #162e45 0%, #1c4e66 50%, #238b8b 100%);
}

.brand-panel::before,
.brand-panel::after {
    position: absolute;
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: 50%;
    content: '';
}

.brand-panel::before {
    width: 34rem;
    height: 34rem;
    right: -18rem;
    bottom: -10rem;
}

.brand-panel::after {
    width: 21rem;
    height: 21rem;
    right: -10rem;
    bottom: -3.5rem;
}

.brand-mark {
    position: relative;
    z-index: 1;
    display: grid;
    width: 4.5rem;
    height: 4.5rem;
    place-items: center;
    border: 1px solid rgba(255, 255, 255, 0.26);
    border-radius: 1.25rem;
    background: rgba(255, 255, 255, 0.1);
    box-shadow: 0 1.5rem 4rem rgba(5, 24, 38, 0.22);
    backdrop-filter: blur(12px);
}

.brand-mark__core,
.mobile-brand__sun {
    display: block;
    border-radius: 50%;
    background: #ffcb6f;
    box-shadow: 0 0 0 0.45rem rgba(255, 203, 111, 0.16), 0 0 2rem rgba(255, 203, 111, 0.65);
}

.brand-mark__core {
    width: 1.65rem;
    height: 1.65rem;
}

.brand-copy {
    position: relative;
    z-index: 1;
    max-width: 34rem;
}

.brand-name,
.eyebrow {
    margin-bottom: 1rem;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.18em;
}

.brand-name {
    color: #ffda95;
}

.brand-copy h1 {
    max-width: 30rem;
    margin-bottom: 1.5rem;
    font-size: clamp(2.8rem, 5vw, 5.4rem);
    font-weight: 600;
    letter-spacing: -0.045em;
    line-height: 0.98;
}

.brand-copy > p:last-child {
    max-width: 29rem;
    color: rgba(255, 255, 255, 0.74);
    font-size: 1.05rem;
    line-height: 1.75;
}

.trust-note {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: rgba(255, 255, 255, 0.68);
    font-size: 0.9rem;
}

.trust-note i {
    color: #ffda95;
    font-size: 1.1rem;
}

.form-panel {
    display: grid;
    align-items: center;
    padding: clamp(2rem, 7vw, 7rem);
}

.form-wrap {
    width: 100%;
    max-width: 29rem;
    margin-inline: auto;
}

.mobile-brand {
    display: none;
}

.form-heading {
    margin-bottom: 2.25rem;
}

.eyebrow {
    margin-bottom: 0.75rem;
    color: rgb(var(--v-theme-primary));
}

.form-heading h2 {
    margin-bottom: 0.65rem;
    color: rgb(var(--v-theme-on-surface));
    font-size: clamp(2rem, 4vw, 2.75rem);
    font-weight: 600;
    letter-spacing: -0.035em;
    line-height: 1.1;
}

.form-heading p:last-child,
.support-copy {
    color: rgb(var(--v-theme-on-surface-variant));
}

.form-options {
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 3rem;
    margin: 0.15rem 0 1.4rem;
}

.security-label {
    color: rgb(var(--v-theme-on-surface-variant));
    font-size: 0.78rem;
}

.sign-in-button {
    width: 100%;
    min-height: 3.25rem;
    font-size: 1rem;
}

.support-copy {
    margin-top: 2.25rem;
    font-size: 0.9rem;
    text-align: center;
}

@media (max-width: 900px) {
    .login-shell {
        display: block;
    }

    .brand-panel {
        display: none;
    }

    .form-panel {
        min-height: 100vh;
        padding: 2rem 1.35rem;
    }

    .mobile-brand {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        margin-bottom: 3.5rem;
        color: rgb(var(--v-theme-on-surface));
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 0.17em;
    }

    .mobile-brand__sun {
        width: 1.35rem;
        height: 1.35rem;
    }
}

@media (max-width: 460px) {
    .form-options {
        align-items: flex-start;
        flex-direction: column;
        gap: 0.5rem;
    }

    .security-label {
        padding-left: 0.2rem;
    }
}
</style>
