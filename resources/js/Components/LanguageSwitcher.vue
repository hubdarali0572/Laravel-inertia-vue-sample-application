<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import { useI18n } from "@/composables/useI18n";

defineProps({
    variant: {
        type: String,
        default: "header",
        validator: (value) => ["header", "dropdown"].includes(value),
    },
});

const { t, locale } = useI18n();
const locales = computed(() => usePage().props.locales || []);

const activeIndex = computed(() => {
    const index = locales.value.findIndex((item) => item.code === locale.value);

    return index >= 0 ? index : 0;
});

const indicatorStyle = computed(() => {
    const count = Math.max(locales.value.length, 1);

    return {
        width: `calc((100% - 4px) / ${count})`,
        transform: `translateX(calc(${activeIndex.value} * 100%))`,
    };
});

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
        v-if="variant === 'dropdown'"
        class="theme-dropdown-lang"
        role="group"
        :aria-label="t('header.language')"
    >
        <span class="theme-dropdown-icon">
            <svg
                class="h-3.5 w-3.5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                stroke-width="2"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"
                />
            </svg>
        </span>
        <span class="min-w-0 flex-1 truncate">{{ t("header.language") }}</span>

        <div class="theme-lang-pill" dir="ltr" role="presentation">
            <span
                class="theme-lang-pill-indicator"
                aria-hidden="true"
                :style="indicatorStyle"
            />
            <button
                v-for="item in locales"
                :key="item.code"
                type="button"
                class="theme-lang-pill-btn"
                :class="{ 'is-active': locale === item.code }"
                :title="item.name"
                :aria-label="item.name"
                :aria-pressed="locale === item.code"
                @click="switchLocale(item.code)"
            >
                {{ item.short }}
            </button>
        </div>
    </div>

    <div
        v-else
        class="theme-lang-pill theme-lang-pill-standalone"
        dir="ltr"
        role="group"
        :aria-label="t('header.language')"
    >
        <span
            class="theme-lang-pill-indicator"
            aria-hidden="true"
            :style="indicatorStyle"
        />
        <button
            v-for="item in locales"
            :key="item.code"
            type="button"
            class="theme-lang-pill-btn"
            :class="{ 'is-active': locale === item.code }"
            :title="item.name"
            :aria-label="item.name"
            :aria-pressed="locale === item.code"
            @click="switchLocale(item.code)"
        >
            {{ item.short }}
        </button>
    </div>
</template>
