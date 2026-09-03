<script setup>
import { ref } from "vue";

const props = defineProps({
    question: { type: String, required: true },
});

const open = ref(false);
</script>

<template>
    <div class="ps-accordion">
        <button
            type="button"
            class="ps-accordion-btn"
            :aria-expanded="open"
            @click="open = !open"
        >
            <span>{{ question }}</span>
            <span aria-hidden="true">{{ open ? "−" : "+" }}</span>
        </button>
        <div v-if="open" class="ps-accordion-body">
            <slot />
        </div>
    </div>
</template>
