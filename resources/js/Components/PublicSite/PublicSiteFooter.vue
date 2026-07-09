<script setup>
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

const page = usePage();

const laravelVersion = computed(() => page.props.laravelVersion);
const phpVersion = computed(() => page.props.phpVersion);
</script>

<template>
    <footer
        class="border-t border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-950"
    >
        <div class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
            <div
                class="flex flex-col sm:flex-row items-center justify-between gap-4"
            >
                <div class="flex items-center gap-3">
                    <ApplicationLogo class="h-6 w-auto fill-slate-400" />
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Precision-Engineered Governance for Unified Digital
                        Media
                    </p>
                </div>
                <p class="text-xs text-slate-400 dark:text-slate-500">
                    Laravel v{{ laravelVersion }} &middot; PHP v{{ phpVersion }}
                </p>
            </div>
        </div>
    </footer>
</template>
