<script setup>
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
        default: false,
    },
});

const isAnchor = computed(() => props.href.startsWith("#") || props.href.includes("#"));

const classes = computed(() => [
    "text-sm font-medium transition-colors",
    props.active
        ? "text-indigo-600 dark:text-indigo-400"
        : "text-slate-600 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400",
]);
</script>

<template>
    <a v-if="isAnchor" :href="href" :class="classes">
        <slot />
    </a>
    <Link v-else :href="href" :class="classes">
        <slot />
    </Link>
</template>
