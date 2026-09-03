<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    items: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <nav class="ps-crumb" aria-label="Breadcrumb">
        <Link :href="route('publicSite.home')">Home</Link>
        <span aria-hidden="true">/</span>
        <template v-for="(item, index) in items" :key="item.label">
            <span v-if="index === items.length - 1" aria-current="page">{{ item.label }}</span>
            <template v-else>
                <Link v-if="item.href" :href="item.href">{{ item.label }}</Link>
                <span v-else>{{ item.label }}</span>
                <span aria-hidden="true">/</span>
            </template>
        </template>
    </nav>
</template>
