<script setup lang="ts">
import AppLayout from '../../components/layout/AppLayout.vue';
import SchemaForm from '../../components/forms/SchemaForm.vue';
import type { FormSchema } from '../../lib/forms';

interface Profile {
    bio: string;
    birthDate: string;
    email: string;
    name: string;
    newsletter: boolean;
    preferredContact: string;
    profileCompletion: number;
    publicProfile: boolean;
    timezone: string;
    yearsOfExperience: number;
}

const props = defineProps<{
    profile: Profile;
    updateUrl: string;
}>();

const profileForm = {
    action: props.updateUrl,
    method: 'put',
    submitLabel: 'Validate profile',
    resetLabel: 'Reset changes',
    successMessage: 'Your profile is valid. No data was saved.',
    items: [
        {
            name: 'email',
            label: 'Email address',
            type: 'email',
            defaultValue: props.profile.email,
            autocomplete: 'email',
            columns: 6,
        },
        {
            name: 'name',
            label: 'Name',
            type: 'text',
            defaultValue: props.profile.name,
            autocomplete: 'name',
            columns: 6,
        },
        {
            name: 'bio',
            label: 'Bio',
            type: 'textarea',
            defaultValue: props.profile.bio,
            hint: 'Tell us a little about yourself.',
            rows: 4,
        },
        {
            name: 'birth_date',
            label: 'Date of birth',
            type: 'date',
            defaultValue: props.profile.birthDate,
            max: new Date().toISOString().slice(0, 10),
            columns: 6,
        },
        {
            name: 'years_of_experience',
            label: 'Years of experience',
            type: 'number',
            defaultValue: props.profile.yearsOfExperience,
            min: 0,
            max: 80,
            step: 1,
            columns: 6,
        },
        {
            name: 'timezone',
            label: 'Timezone',
            type: 'select',
            defaultValue: props.profile.timezone,
            columns: 6,
            options: [
                { label: 'New York', value: 'America/New_York' },
                { label: 'London', value: 'Europe/London' },
                { label: 'Warsaw', value: 'Europe/Warsaw' },
                { label: 'Tokyo', value: 'Asia/Tokyo' },
            ],
        },
        {
            name: 'preferred_contact',
            label: 'Preferred contact method',
            type: 'radio',
            defaultValue: props.profile.preferredContact,
            inline: true,
            columns: 6,
            options: [
                { label: 'Email', value: 'email' },
                { label: 'Phone', value: 'phone' },
            ],
        },
        {
            name: 'newsletter',
            label: 'Receive product news by email',
            type: 'checkbox',
            defaultValue: props.profile.newsletter,
            columns: 6,
        },
        {
            name: 'public_profile',
            label: 'Make profile public',
            type: 'switch',
            defaultValue: props.profile.publicProfile,
            color: 'primary',
            inset: true,
            columns: 6,
        },
        {
            name: 'profile_completion',
            label: 'Profile completion target',
            type: 'range',
            defaultValue: props.profile.profileCompletion,
            min: 0,
            max: 100,
            step: 5,
            showTicks: true,
        },
        {
            name: 'resume',
            label: 'Résumé (PDF)',
            type: 'file',
            defaultValue: null,
            accept: '.pdf,application/pdf',
            showSize: true,
            hint: 'Optional, maximum size 2 MB.',
        },
    ],
} satisfies FormSchema;
</script>

<template>
    <AppLayout>
        <v-card class="mx-auto" max-width="900" variant="flat">
            <v-card-text>
                <SchemaForm :schema="profileForm" />
            </v-card-text>
        </v-card>
    </AppLayout>
</template>
