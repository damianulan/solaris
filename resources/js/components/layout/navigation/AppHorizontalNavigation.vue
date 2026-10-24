<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';

import AppHorizontalNavigationItem from './AppHorizontalNavigationItem.vue';

const page = usePage();
</script>

<template>
    <v-app-bar
        border="b"
        color="background"
        elevation="0"
        height="56"
        order="1"
    >
        <v-slide-group
            class="px-2 flex-grow-1"
            show-arrows
        >
            <div class="d-flex align-center ga-1">
                <AppHorizontalNavigationItem
                    v-for="(navigationItem, index) in page.props.sidebar"
                    :key="`${navigationItem.title}-${index}`"
                    :item="navigationItem"
                />
            </div>
        </v-slide-group>
    </v-app-bar>
</template>
