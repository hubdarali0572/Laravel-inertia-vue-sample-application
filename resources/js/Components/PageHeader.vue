<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    subtitle: {
        type: String,
        default: "",
    },
});
</script>

<template>
    <div class="theme-page-header">
        <div class="min-w-0">
            <h2 class="theme-page-title">{{ title }}</h2>
            <p v-if="subtitle" class="theme-page-subtitle">{{ subtitle }}</p>
        </div>
        <div v-if="$slots.actions || $slots.default" class="theme-page-actions">
            <slot name="actions" />
            <slot />
        </div>
    </div>
</template>
