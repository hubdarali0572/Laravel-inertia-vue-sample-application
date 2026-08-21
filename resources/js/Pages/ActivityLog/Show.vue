<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";
import { useActivityLog } from "@/composables/useActivityLog";
import { useI18n } from "@/composables/useI18n";

const props = defineProps({
    activity: {
        type: Object,
        required: true,
    },
});

const { t } = useI18n();
const {
    formatDate,
    getModelName,
    formatValue,
    getChangeDetails,
    hasChangeDetails,
    actionBadgeClass,
    eventLabel,
} = useActivityLog();

const changes = computed(() => getChangeDetails(props.activity));
const actionKey = computed(
    () => props.activity.description || props.activity.event || "—",
);
const actionDisplay = computed(() => eventLabel(actionKey.value));
const actionDescription = computed(() => {
    const map = {
        created: "activity.record_created",
        updated: "activity.record_updated",
        deleted: "activity.record_deleted",
    };

    return map[actionKey.value] ? t(map[actionKey.value]) : actionDisplay.value;
});
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="t('activity.details_title')" />

        <PageHeader
            :title="t('activity.details_title')"
            :subtitle="t('activity.details_subtitle')"
        >
            <Link :href="route('activity.index')" class="theme-form-back-link">
                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"
                        stroke-width="2.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
                {{ t("activity.back") }}
            </Link>
        </PageHeader>

        <div class="space-y-5">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="theme-form-card">
                    <div class="theme-form-section-header">
                        <h3 class="theme-form-section-title">{{ t("activity.user") }}</h3>
                    </div>
                    <div class="theme-form-body">
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100">
                            {{ activity.causer?.name || t("common.system") }}
                        </p>
                        <p
                            v-if="activity.causer?.email"
                            class="mt-1 truncate text-xs text-slate-500 dark:text-slate-400"
                        >
                            {{ activity.causer.email }}
                        </p>
                    </div>
                </div>

                <div class="theme-form-card">
                    <div class="theme-form-section-header">
                        <h3 class="theme-form-section-title">{{ t("activity.action") }}</h3>
                    </div>
                    <div class="theme-form-body">
                        <span
                            class="inline-flex rounded px-2 py-1 text-[10px] font-black uppercase tracking-wide"
                            :class="actionBadgeClass(actionKey)"
                        >
                            {{ actionDisplay }}
                        </span>
                    </div>
                </div>

                <div class="theme-form-card">
                    <div class="theme-form-section-header">
                        <h3 class="theme-form-section-title">{{ t("activity.module") }}</h3>
                    </div>
                    <div class="theme-form-body">
                        <p class="text-sm font-bold uppercase text-slate-800 dark:text-slate-100">
                            {{ getModelName(activity.subject_type) }}
                        </p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            #{{ activity.subject_id || "—" }}
                        </p>
                    </div>
                </div>

                <div class="theme-form-card">
                    <div class="theme-form-section-header">
                        <h3 class="theme-form-section-title">{{ t("activity.date_time") }}</h3>
                    </div>
                    <div class="theme-form-body">
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100">
                            {{ formatDate(activity.created_at) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="theme-form-card">
                <div class="theme-form-section-header">
                    <h3 class="theme-form-section-title">{{ t("activity.description") }}</h3>
                </div>
                <div class="theme-form-body">
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ actionDescription }}
                    </p>
                </div>
            </div>

            <div class="theme-form-card">
                <div class="theme-form-section-header">
                    <h3 class="theme-form-section-title">{{ t("activity.changes") }}</h3>
                </div>
                <div class="theme-form-body">
                    <div v-if="hasChangeDetails(activity)" class="space-y-3">
                        <div
                            v-for="change in changes"
                            :key="change.field"
                            class="rounded-lg border border-slate-200 bg-slate-50/80 px-4 py-3 dark:border-slate-600 dark:bg-slate-700/40"
                        >
                            <p
                                class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400"
                            >
                                {{ change.label }}
                            </p>

                            <div
                                v-if="change.type === 'updated'"
                                class="mt-2 flex flex-wrap items-center gap-2 text-sm"
                            >
                                <span
                                    class="rounded bg-white px-2.5 py-1 font-medium text-slate-500 line-through dark:bg-slate-800 dark:text-slate-400"
                                >
                                    {{ formatValue(change.old) }}
                                </span>
                                <svg
                                    class="h-4 w-4 shrink-0 text-indigo-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13.5 4.5L21 12m0 0l-8.5 8.5M21 12H3"
                                    />
                                </svg>
                                <span
                                    class="rounded bg-indigo-50 px-2.5 py-1 font-bold text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-200"
                                >
                                    {{ formatValue(change.new) }}
                                </span>
                            </div>

                            <div v-else-if="change.type === 'created'" class="mt-2">
                                <span
                                    class="inline-flex rounded bg-indigo-50 px-2.5 py-1 text-sm font-bold text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-200"
                                >
                                    {{ formatValue(change.new) }}
                                </span>
                            </div>

                            <div v-else class="mt-2">
                                <span
                                    class="inline-flex rounded bg-slate-200 px-2.5 py-1 text-sm font-medium text-slate-700 line-through dark:bg-slate-600 dark:text-slate-200"
                                >
                                    {{ formatValue(change.old) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <p v-else class="text-sm text-slate-400 dark:text-slate-500">
                        {{ t("activity.no_detail_long") }}
                    </p>
                </div>
            </div>

            <div class="theme-form-card">
                <div class="theme-form-section-header">
                    <h3 class="theme-form-section-title">{{ t("activity.metadata") }}</h3>
                </div>
                <div class="theme-form-body">
                    <dl class="grid grid-cols-1 gap-x-8 gap-y-3 sm:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <dt class="theme-form-label mb-1">{{ t("activity.id") }}</dt>
                            <dd class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                                #{{ activity.id }}
                            </dd>
                        </div>
                        <div>
                            <dt class="theme-form-label mb-1">{{ t("activity.log_name") }}</dt>
                            <dd class="text-sm font-semibold capitalize text-slate-800 dark:text-slate-100">
                                {{ activity.log_name || "—" }}
                            </dd>
                        </div>
                        <div>
                            <dt class="theme-form-label mb-1">{{ t("activity.event") }}</dt>
                            <dd class="text-sm font-semibold capitalize text-slate-800 dark:text-slate-100">
                                {{ eventLabel(activity.event || actionKey) }}
                            </dd>
                        </div>
                        <div>
                            <dt class="theme-form-label mb-1">{{ t("activity.causer_id") }}</dt>
                            <dd class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                                {{ activity.causer_id || "—" }}
                            </dd>
                        </div>
                        <div>
                            <dt class="theme-form-label mb-1">{{ t("activity.subject_type") }}</dt>
                            <dd class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                                {{ activity.subject_type || "—" }}
                            </dd>
                        </div>
                        <div>
                            <dt class="theme-form-label mb-1">{{ t("activity.batch") }}</dt>
                            <dd class="truncate text-sm font-semibold text-slate-800 dark:text-slate-100">
                                {{ activity.batch_uuid || "—" }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
