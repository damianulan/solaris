<script setup lang="ts">
import type { UserContext } from '../../lib/types';
import AppUserMenu from './AppUserMenu.vue';
import ThemeSwitcher from './ThemeSwitcher.vue';

const props = defineProps<{
    user?: UserContext;
}>();
</script>

<template>
    <ThemeSwitcher />

    <AppUserMenu :user="props.user" />
</template>
