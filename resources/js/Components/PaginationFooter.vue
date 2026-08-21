<script setup>
import { Link } from "@inertiajs/vue3";
import { useI18n } from "@/composables/useI18n";

const props = defineProps({
    paginator: {
        type: Object,
        required: true,
    },
    noun: {
        type: String,
        default: "entries",
    },
});

const { t } = useI18n();
</script>

<template>
    <div class="theme-table-footer">
        <div class="theme-table-footer-meta">
            {{
                t("pagination.showing", {
                    from: paginator.from || 0,
                    to: paginator.to || 0,
                    total: paginator.total || 0,
                    noun: t(`pagination.${noun}`),
                })
            }}
        </div>
        <div class="flex flex-wrap items-center justify-center gap-1">
            <template v-for="(link, k) in paginator.links" :key="k">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    class="theme-pagination-btn"
                    :class="
                        link.active
                            ? 'theme-pagination-active'
                            : 'theme-pagination-inactive'
                    "
                    v-html="link.label"
                />
                <span
                    v-else
                    class="theme-pagination-btn theme-pagination-disabled"
                    v-html="link.label"
                />
            </template>
        </div>
    </div>
</template>
