<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    alt: {
        type: String,
        default: "",
    },
});

const src = computed(
    () => usePage().props.institution?.logo_url || "/images/logo.jpeg",
);
</script>

<template>
    <img :src="src" :alt="props.alt" class="ps-logo-img" />
</template>
