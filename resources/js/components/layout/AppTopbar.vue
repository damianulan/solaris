<script setup lang="ts">
import { computed } from 'vue';

import type { UserContext } from '../../lib/types';

const props = defineProps<{
    user?: UserContext;
}>();

const userInitial = computed(() => props.user?.name.charAt(0).toUpperCase() ?? 'G');
</script>

<template>
    <v-menu location="bottom end">
        <template #activator="{ props: activatorProps }">
            <v-btn v-bind="activatorProps" icon aria-label="Open user menu">
                <v-avatar color="primary" size="36">
                    <span class="text-body-2">{{ userInitial }}</span>
                </v-avatar>
            </v-btn>
        </template>

        <v-card min-width="240">
            <v-list>
                <v-list-item
                    :subtitle="user?.email ?? 'Not signed in'"
                    :title="user?.name ?? 'Guest'"
                    prepend-icon="bi-person-circle"
                />
            </v-list>

            <v-divider />

            <v-list density="compact" nav>
                <v-list-item prepend-icon="bi-gear" title="Settings" disabled />
                <v-list-item prepend-icon="bi-box-arrow-right" title="Sign out" disabled />
            </v-list>
        </v-card>
    </v-menu>
</template>
