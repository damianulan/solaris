<script setup lang="ts">
import { useTranslations } from '../../lib/translations';

const { translate } = useTranslations();
</script>

<template>
    <v-footer class="text-body-2 text-medium-emphasis left-0" border>
        <span>{{ translate('frontend.footer.copyright', { year: new Date().getFullYear() }) }}</span>
    </v-footer>
</template>
