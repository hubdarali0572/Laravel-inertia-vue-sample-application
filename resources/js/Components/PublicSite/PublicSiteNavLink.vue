<script setup>
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    href: { type: String, required: true },
    active: { type: Boolean, default: false },
    mobile: { type: Boolean, default: false },
    external: { type: Boolean, default: false },
});

const emit = defineEmits(["navigate"]);

const classes = computed(() =>
    props.mobile
        ? ["ps-mobile-link", props.active ? "is-active" : ""]
        : ["ps-nav-link", props.active ? "is-active" : ""],
);
</script>

<template>
    <a
        v-if="external || href.startsWith('http')"
        :href="href"
        :class="classes"
        @click="emit('navigate')"
    >
        <slot />
    </a>
    <Link v-else :href="href" :class="classes" @click="emit('navigate')">
        <slot />
    </Link>
</template>
