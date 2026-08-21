<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'theme-public-nav is-active inline-flex items-center border-b-2 border-[var(--color-heading)] px-1 pt-1 text-sm font-medium leading-5'
        : 'theme-public-nav inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium leading-5',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
