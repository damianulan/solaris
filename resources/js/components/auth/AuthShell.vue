<script setup lang="ts">
import { useTranslations } from '../../lib/translations';
import AppBrand from '../layout/AppBrand.vue';
import ThemeSwitcher from '../layout/ThemeSwitcher.vue';

defineProps<{
    description: string;
    eyebrow: string;
    title: string;
}>();

const { translate } = useTranslations();
</script>

<template>
    <v-app>
        <v-main class="auth-page">
            <div class="auth-shell">
                <section class="brand-panel" aria-labelledby="welcome-heading">
                    <div class="brand-copy">
                        <AppBrand class="desktop-brand" />
                        <h1 id="welcome-heading">{{ translate('frontend.auth.brand.heading') }}</h1>
                        <p>{{ translate('frontend.auth.brand.description') }}</p>
                    </div>

                    <div class="trust-note">
                        <i class="bi bi-shield-check" aria-hidden="true" />
                        <span>{{ translate('frontend.auth.brand.trust') }}</span>
                    </div>
                </section>

                <section class="form-panel" aria-labelledby="auth-form-heading">
                    <ThemeSwitcher class="theme-switcher" />

                    <div class="form-wrap">
                        <AppBrand class="mobile-brand" />

                        <div class="form-heading">
                            <p class="eyebrow">{{ eyebrow }}</p>
                            <h2 id="auth-form-heading">{{ title }}</h2>
                            <p class="text-accent">{{ description }}</p>
                        </div>

                        <slot />
                    </div>
                </section>
            </div>
        </v-main>
    </v-app>
</template>

<style scoped>
.auth-page {
    min-height: 100vh;
    background:
        radial-gradient(
            circle at 82% 12%,
            rgba(var(--v-theme-primary), 0.12),
            rgba(var(--v-theme-primary), 0) 32rem
        ),
        rgb(var(--v-theme-surface));
}

.auth-shell {
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
    color: rgb(var(--v-theme-on-primary));
    background:
        radial-gradient(
            circle at 82% 18%,
            rgba(var(--v-theme-accent), 0.34),
            rgba(var(--v-theme-accent), 0) 16rem
        ),
        linear-gradient(
            145deg,
            rgb(var(--v-theme-secondary)) 0%,
            rgb(var(--v-theme-primary)) 50%,
            rgb(var(--v-theme-secondary)) 100%
        );
}

.brand-panel::before,
.brand-panel::after {
    position: absolute;
    border: 1px solid rgba(var(--v-theme-on-primary), 0.14);
    border-radius: 50%;
    content: '';
}

.brand-panel::before {
    right: -18rem;
    bottom: -10rem;
    width: 34rem;
    height: 34rem;
}

.brand-panel::after {
    right: -10rem;
    bottom: -3.5rem;
    width: 21rem;
    height: 21rem;
}

.brand-copy {
    position: relative;
    z-index: 1;
    max-width: 34rem;
}

.eyebrow {
    margin-bottom: 1rem;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.18em;
}

.desktop-brand {
    margin-bottom: 1.5rem;
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
    color: rgba(var(--v-theme-on-primary), 0.74);
    font-size: 1.05rem;
    line-height: 1.75;
}

.trust-note {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: rgba(var(--v-theme-on-primary), 0.68);
    font-size: 0.9rem;
}

.trust-note i {
    color: rgb(var(--v-theme-accent));
    font-size: 1.1rem;
}

.form-panel {
    position: relative;
    display: grid;
    align-items: center;
    padding: clamp(2rem, 7vw, 7rem);
}

.theme-switcher {
    position: absolute;
    z-index: 1;
    inset-block-start: 1.5rem;
    inset-inline-end: 1.5rem;
}

.form-wrap {
    width: 100%;
    max-width: 32rem;
    margin-inline: auto;
    padding-block: 2rem;
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

:deep(.auth-submit) {
    width: 100%;
    min-height: 3.25rem;
    font-size: 1rem;
}

:deep(.auth-footer) {
    margin-top: 2rem;
    color: rgb(var(--v-theme-on-surface-variant));
    font-size: 0.9rem;
    text-align: center;
}

:deep(.auth-link) {
    color: rgb(var(--v-theme-primary));
    font-weight: 600;
}

@media (max-width: 900px) {
    .auth-shell {
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
}
</style>
