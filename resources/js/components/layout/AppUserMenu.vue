<script setup lang="ts">
import { computed } from 'vue';

import { useTranslations } from '../../lib/translations';
import type { UserContext } from '../../lib/types';

const props = defineProps<{
    user?: UserContext;
}>();

const { translate } = useTranslations();

const userInitial = computed(() => (props.user?.name ?? translate('frontend.user.guest')).charAt(0).toUpperCase());
</script>

<template>
    <v-menu location="bottom end">
        <template #activator="{ props: activatorProps }">
            <v-btn
                v-bind="activatorProps"
                :aria-label="translate('frontend.user.open_menu')"
                icon
            >
                <v-avatar color="primary" size="36">
                    <span class="text-body-2">{{ userInitial }}</span>
                </v-avatar>
            </v-btn>
        </template>

        <v-card min-width="240">
            <v-list>
                <v-list-item
                    :subtitle="user?.email ?? translate('frontend.user.not_signed_in')"
                    :title="user?.name ?? translate('frontend.user.guest')"
                    prepend-icon="bi-person-circle"
                />
            </v-list>

            <v-divider />

            <v-list density="compact" nav>
                <v-list-item
                    :title="translate('frontend.user.settings')"
                    prepend-icon="bi-gear"
                    disabled
                />
                <v-list-item
                    :title="translate('frontend.user.sign_out')"
                    prepend-icon="bi-box-arrow-right"
                    disabled
                />
            </v-list>
        </v-card>
    </v-menu>
</template>
