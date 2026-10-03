<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AppLayout from '../components/layout/AppLayout.vue';
import { useTranslations } from '../lib/translations';

const { translate } = useTranslations();
</script>

<template>
    <Head :title="translate('frontend.home.title')" />

    <AppLayout />
</template>
