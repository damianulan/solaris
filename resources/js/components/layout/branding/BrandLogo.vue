<script setup lang="ts">
import { useTranslations } from '../../../lib/translations';

const props = withDefaults(defineProps<{
    color?: string;
}>(), {
    color: 'brand',
});

const { translate } = useTranslations();
const solarisLogoUrl = '/images/solaris-logo.svg';
</script>

<template>
    <span
        :aria-label="translate('frontend.visual.logo_alt')"
        :class="`bg-${props.color}`"
        class="app-bar-logo"
        role="img"
        :style="{ '--solaris-logo-url': `url(${solarisLogoUrl})` }"
    />
</template>

<style scoped>
.app-bar-logo {
    display: inline-block;
    height: 32px;
    width: 37px;
    -webkit-mask: var(--solaris-logo-url) center / contain no-repeat;
    mask: var(--solaris-logo-url) center / contain no-repeat;
}
</style>
