<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import { useI18n } from "@/composables/useI18n";

const { t, locale } = useI18n();
const locales = computed(() => usePage().props.locales || []);

const switchLocale = (code) => {
    if (!code || code === locale.value) {
        return;
    }

    router.post(
        route("locale.update"),
        { locale: code },
        { preserveScroll: true, preserveState: false },
    );
};
</script>

<template>
    <div
        class="theme-header-control gap-0 overflow-hidden p-0"
        role="group"
        :aria-label="t('header.language')"
    >
        <button
            v-for="item in locales"
            :key="item.code"
            type="button"
            class="inline-flex h-full min-w-9 items-center justify-center px-2.5 text-[11px] font-semibold tracking-wide transition-colors"
            :class="
                locale === item.code
                    ? 'bg-indigo-600 text-white'
                    : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-700 dark:text-slate-200 dark:hover:bg-indigo-500/10'
            "
            :title="item.name"
            :aria-label="item.name"
            :aria-pressed="locale === item.code"
            @click="switchLocale(item.code)"
        >
            {{ item.short }}
        </button>
    </div>
</template>
