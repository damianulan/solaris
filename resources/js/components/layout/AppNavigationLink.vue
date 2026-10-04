<script setup lang="ts">
import { defineProps } from "vue";
import type { NavigationLink } from "../../lib/types";

defineProps<NavigationLink>();
</script>

<template>
    <v-list-item
        density="comfortable"
        :active="active"
        :prepend-icon="icon ?? undefined"
        :title="title"
        :disabled="disabled"
        :link="true"
        :nav="true"
        :href="link"
        class="font-weight-regular"
        prepend-gap="1rem"
        variant="flat"
    />
</template>
