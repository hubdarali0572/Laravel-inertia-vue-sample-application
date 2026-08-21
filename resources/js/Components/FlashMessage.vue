<script setup>
import { computed, ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const visible = ref(false);
let timer = null;

const flash = computed(() => page.props.flash || {});
const message = computed(
    () => flash.value.success || flash.value.danger || flash.value.error || "",
);
const isSuccess = computed(() => !!flash.value.success);

const startTimer = () => {
    visible.value = true;
    if (timer) {
        clearTimeout(timer);
    }
    timer = setTimeout(() => {
        visible.value = false;
    }, 5000);
};

watch(
    () => [flash.value.success, flash.value.danger, flash.value.error],
    ([success, danger, error]) => {
        if (success || danger || error) {
            startTimer();
        }
    },
    { immediate: true },
);
</script>

<template>
    <transition name="fade">
        <div
            v-if="visible && message"
            class="mb-5 flex items-center rounded-r-xl border-l-4 p-4 shadow-sm"
            :class="
                isSuccess
                    ? 'border-indigo-500 bg-indigo-50 text-indigo-800 dark:bg-indigo-500/10 dark:text-indigo-200'
                    : 'border-slate-400 bg-slate-100 text-slate-700 dark:bg-slate-700/80 dark:text-slate-200'
            "
        >
            <div class="shrink-0">
                <svg
                    v-if="isSuccess"
                    class="h-5 w-5"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    />
                </svg>
                <svg
                    v-else
                    class="h-5 w-5"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                    />
                </svg>
            </div>
            <p class="ml-3 text-sm font-bold">{{ message }}</p>
            <button
                type="button"
                class="ml-auto opacity-50 transition-opacity hover:opacity-100"
                @click="visible = false"
            >
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        d="M6 18L18 6M6 6l12 12"
                        stroke-width="2.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
            </button>
        </div>
    </transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.4s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
