<script setup>
import { onMounted, onUnmounted, watch } from "vue";

const props = defineProps({
    open: { type: Boolean, default: false },
    title: { type: String, default: "" },
});

const emit = defineEmits(["close"]);

const onKey = (event) => {
    if (event.key === "Escape") {
        emit("close");
    }
};

watch(
    () => props.open,
    (open) => {
        document.body.style.overflow = open ? "hidden" : "";
    },
);

onMounted(() => document.addEventListener("keydown", onKey));
onUnmounted(() => {
    document.removeEventListener("keydown", onKey);
    document.body.style.removeProperty("overflow");
});
</script>

<template>
    <div v-if="open" class="ps-modal" role="dialog" aria-modal="true" :aria-label="title || 'Dialog'">
        <div class="ps-modal-backdrop" @click="emit('close')" />
        <div class="ps-modal-panel">
            <div class="ps-row" style="justify-content: space-between; margin-bottom: 1rem">
                <h2 class="ps-h3">{{ title }}</h2>
                <button type="button" class="ps-menu-btn" aria-label="Close" @click="emit('close')">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <slot />
        </div>
    </div>
</template>
