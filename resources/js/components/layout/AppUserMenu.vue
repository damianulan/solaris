<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import { useTranslations } from '../../lib/translations';

const page = usePage();
const { translate } = useTranslations();

const user = computed(() => page.props.auth.user);
const userInitial = computed(() => (user.value?.name ?? translate('frontend.user.guest')).charAt(0).toUpperCase());
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
                    <v-img
                        v-if="user?.avatarUrl"
                        :alt="user.name"
                        :src="user.avatarUrl"
                        cover
                    />
                    <span v-else class="text-body-2">{{ userInitial }}</span>
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
                    v-if="user"
                    :href="user.profileUrl"
                    :tag="Link"
                    :title="translate('frontend.user.profile')"
                    prepend-icon="bi-person"
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
