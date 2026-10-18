<script setup lang="ts">
import AppLayout from '../../components/layout/AppLayout.vue';
import SchemaForm from '../../components/forms/SchemaForm.vue';
import type { FormSchema } from '../../lib/forms';
import { useTranslations } from '../../lib/translations';

interface Profile {
    avatarUrl: string | null;
    bio: string | null;
    email: string | null;
    firstname: string;
    lang: string;
    lastname: string;
    middlename: string | null;
    timezone: string;
    username: string | null;
}

const props = defineProps<{
    availableLocales: string[];
    availableTimezones: string[];
    filepondUrl: string;
    profile: Profile;
    updateUrl: string;
}>();

const { translate } = useTranslations();

const profileForm = {
    action: props.updateUrl,
    method: 'put',
    submitLabel: translate('frontend.profile.save'),
    resetLabel: translate('frontend.profile.reset'),
    successMessage: translate('frontend.profile.success'),
    items: [
        {
            name: 'firstname',
            label: translate('frontend.profile.first_name'),
            type: 'text',
            defaultValue: props.profile.firstname,
            autocomplete: 'given-name',
            columns: 6,
        },
        {
            name: 'lastname',
            label: translate('frontend.profile.last_name'),
            type: 'text',
            defaultValue: props.profile.lastname,
            autocomplete: 'family-name',
            columns: 6,
        },
        {
            name: 'middlename',
            label: translate('frontend.profile.middle_name'),
            type: 'text',
            defaultValue: props.profile.middlename ?? '',
            autocomplete: 'additional-name',
            columns: 6,
        },
        {
            name: 'username',
            label: translate('frontend.profile.username'),
            type: 'text',
            defaultValue: props.profile.username ?? '',
            autocomplete: 'username',
            columns: 6,
        },
        {
            name: 'email',
            label: translate('frontend.profile.email'),
            type: 'email',
            defaultValue: props.profile.email ?? '',
            autocomplete: 'email',
            columns: 6,
        },
        {
            name: 'lang',
            label: translate('frontend.profile.language'),
            type: 'select',
            defaultValue: props.profile.lang,
            columns: 6,
            options: props.availableLocales.map((locale) => ({
                label: translate(`frontend.profile.languages.${locale}`),
                value: locale,
            })),
        },
        {
            name: 'timezone',
            label: translate('frontend.profile.timezone'),
            type: 'select',
            defaultValue: props.profile.timezone,
            options: props.availableTimezones.map((timezone) => ({
                label: timezone.replaceAll('_', ' '),
                value: timezone,
            })),
        },
        {
            name: 'bio',
            label: translate('frontend.profile.bio'),
            type: 'textarea',
            defaultValue: props.profile.bio ?? '',
            hint: translate('frontend.profile.bio_hint'),
            rows: 4,
        },
        {
            name: 'avatar',
            label: translate('frontend.profile.avatar'),
            type: 'filepond',
            defaultValue: null,
            acceptedFileTypes: ['image/jpeg', 'image/png'],
            maxFileSize: '2MB',
            serverUrl: props.filepondUrl,
            hint: translate('frontend.profile.avatar_hint'),
        },
    ],
} satisfies FormSchema;
</script>

<template>
    <AppLayout>
        <v-card class="mx-auto" max-width="900" variant="flat">
            <v-card-text>
                <div v-if="profile.avatarUrl" class="d-flex justify-center mb-6">
                    <v-avatar size="112">
                        <v-img
                            :src="profile.avatarUrl"
                            :alt="translate('frontend.profile.current_avatar_alt')"
                            cover
                        />
                    </v-avatar>
                </div>

                <SchemaForm :schema="profileForm" />
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
