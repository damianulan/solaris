<script setup lang="ts">
import { useTranslations } from '../../lib/translations';
import { usePage } from '@inertiajs/vue3';

const { translate } = useTranslations();
const page = usePage();

</script>

<template>
    <v-footer class="text-body-2 text-medium-emphasis left-0" border>
        <span>{{ translate('frontend.footer.copyright', { year: new Date().getFullYear(), name: page.props.page.title }) }}</span>
    </v-footer>
</template>
