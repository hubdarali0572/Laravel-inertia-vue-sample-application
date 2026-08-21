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
    mobile: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["navigate"]);

const isAnchor = computed(
    () => props.href.startsWith("#") || props.href.includes("#"),
);

const classes = computed(() => {
    if (props.mobile) {
        return [
            "theme-public-nav-mobile",
            props.active ? "is-active" : "",
        ];
    }

    return [
        "theme-public-nav inline-flex items-center",
        props.active ? "is-active" : "",
    ];
});
</script>

<template>
    <a
        v-if="isAnchor"
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
