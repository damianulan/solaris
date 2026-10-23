<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, ref } from 'vue';

const props = withDefaults(defineProps<{
    delay?: number;
    size?: number | string;
}>(), {
    delay: 100,
    size: '5.5rem',
});

const pendingVisitIds = ref(new Set<string>());
const isLoading = ref(false);
const loadingIndicatorSize = computed(() => typeof props.size === 'number' ? `${props.size}px` : props.size);
let loadingDelayTimer: number | undefined;

function clearLoadingDelayTimer(): void {
    if (loadingDelayTimer === undefined) {
        return;
    }

    window.clearTimeout(loadingDelayTimer);
    loadingDelayTimer = undefined;
}

function scheduleLoadingOverlay(): void {
    const delay = Math.max(0, props.delay);

    if (delay === 0) {
        isLoading.value = true;

        return;
    }

    loadingDelayTimer = window.setTimeout(() => {
        loadingDelayTimer = undefined;
        isLoading.value = pendingVisitIds.value.size > 0;
    }, delay);
}

const removeStartListener = router.on('start', (event) => {
    if (!event.detail.visit.showProgress) {
        return;
    }

    const wasIdle = pendingVisitIds.value.size === 0;

    pendingVisitIds.value = new Set([...pendingVisitIds.value, event.detail.visit.id]);

    if (wasIdle) {
        scheduleLoadingOverlay();
    }
});

const removeFinishListener = router.on('finish', (event) => {
    const remainingVisitIds = new Set(pendingVisitIds.value);

    remainingVisitIds.delete(event.detail.visit.id);
    pendingVisitIds.value = remainingVisitIds;

    if (remainingVisitIds.size > 0) {
        return;
    }

    clearLoadingDelayTimer();
    isLoading.value = false;
});

onBeforeUnmount(() => {
    clearLoadingDelayTimer();
    removeStartListener();
    removeFinishListener();
});
</script>

<template>
    <v-overlay
        class="align-center justify-center"
        :model-value="isLoading"
        no-click-animation
        opacity="0.56"
        persistent
        scrim="background"
        transition="fade-transition"
        :z-index="3000"
    >
        <div
            class="loading-indicator"
            aria-hidden="true"
            :style="{ '--loading-indicator-size': loadingIndicatorSize }"
        >
            <span class="orbit orbit-outer" />
            <span class="orbit orbit-middle" />
            <span class="orbit orbit-inner" />
            <span class="loader-core" />
        </div>
    </v-overlay>
</template>

<style scoped>
:deep(.v-overlay__scrim) {
    backdrop-filter: blur(3px);
}

.loading-indicator {
    position: relative;
    display: grid;
    width: var(--loading-indicator-size);
    height: var(--loading-indicator-size);
    place-items: center;
    border-radius: 50%;
    background:
        radial-gradient(circle, rgba(var(--v-theme-primary), 0.11), transparent 58%),
        rgba(var(--v-theme-surface), 0.82);
    box-shadow:
        0 1.5rem 4rem rgba(0, 0, 0, 0.2),
        inset 0 0 2.5rem rgba(var(--v-theme-primary), 0.08);
    backdrop-filter: blur(18px);
    animation: loader-breathe 2.4s ease-in-out infinite;
}

.orbit {
    position: absolute;
    border-radius: 50%;
}

.orbit::after {
    position: absolute;
    border-radius: 50%;
    background: rgb(var(--v-theme-accent));
    box-shadow: 0 0 1rem rgba(var(--v-theme-accent), 0.9);
    content: '';
}

.orbit-outer {
    width: 72.38%;
    height: 72.38%;
    border: 2px solid rgba(var(--v-theme-primary), 0.15);
    border-top-color: rgb(var(--v-theme-primary));
    border-right-color: rgba(var(--v-theme-accent), 0.65);
    animation: orbit-spin 1.8s linear infinite;
}

.orbit-outer::after {
    top: 3.33%;
    right: 6.19%;
    width: 8.55%;
    height: 8.55%;
}

.orbit-middle {
    width: 50.95%;
    height: 50.95%;
    border: 1px dashed rgba(var(--v-theme-accent), 0.72);
    animation: orbit-spin-reverse 2.6s linear infinite;
}

.orbit-middle::after {
    right: -2.8%;
    bottom: 19.63%;
    width: 8.41%;
    height: 8.41%;
    background: rgb(var(--v-theme-primary));
    box-shadow: 0 0 0.8rem rgba(var(--v-theme-primary), 0.9);
}

.orbit-inner {
    width: 30.95%;
    height: 30.95%;
    border: 2px solid transparent;
    border-top-color: rgba(var(--v-theme-primary), 0.9);
    border-bottom-color: rgba(var(--v-theme-accent), 0.8);
    animation: orbit-spin 1s ease-in-out infinite;
}

.orbit-inner::after {
    display: none;
}

.loader-core {
    width: 12.86%;
    height: 12.86%;
    border-radius: 50%;
    background: radial-gradient(
        circle at 35% 30%,
        rgb(var(--v-theme-accent)),
        rgb(var(--v-theme-primary)) 62%,
        rgb(var(--v-theme-secondary))
    );
    box-shadow:
        0 0 1.1rem rgba(var(--v-theme-primary), 0.9),
        0 0 2.4rem rgba(var(--v-theme-accent), 0.42);
    animation: core-pulse 1.15s ease-in-out infinite alternate;
}

@keyframes orbit-spin {
    to {
        transform: rotate(360deg);
    }
}

@keyframes orbit-spin-reverse {
    to {
        transform: rotate(-360deg);
    }
}

@keyframes loader-breathe {
    50% {
        box-shadow:
            0 1.75rem 4.5rem rgba(0, 0, 0, 0.25),
            inset 0 0 3.25rem rgba(var(--v-theme-primary), 0.14);
        transform: scale(1.025);
    }
}

@keyframes core-pulse {
    to {
        opacity: 0.72;
        transform: scale(0.78);
    }
}

@media (prefers-reduced-motion: reduce) {
    .loading-indicator,
    .orbit,
    .loader-core {
        animation: none;
    }
}
</style>
