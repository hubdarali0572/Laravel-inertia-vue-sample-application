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

        <div class="theme-log-page">
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

            <div class="theme-form-card">
                <dl class="theme-log-meta">
                    <div class="theme-log-meta-item">
                        <dt>{{ t("activity.user") }}</dt>
                        <dd>{{ activity.causer?.name || t("common.system") }}</dd>
                        <dd
                            v-if="activity.causer?.email"
                            class="theme-log-meta-sub"
                        >
                            {{ activity.causer.email }}
                        </dd>
                    </div>

                    <div class="theme-log-meta-item">
                        <dt>{{ t("activity.action") }}</dt>
                        <dd>
                            <span :class="actionBadgeClass(actionKey)">
                                {{ actionDisplay }}
                            </span>
                        </dd>
                        <dd class="theme-log-meta-sub">{{ actionDescription }}</dd>
                    </div>

                    <div class="theme-log-meta-item">
                        <dt>{{ t("activity.module") }}</dt>
                        <dd>{{ getModelName(activity.subject_type) }}</dd>
                        <dd class="theme-log-meta-sub">
                            #{{ activity.subject_id || "—" }}
                        </dd>
                    </div>

                    <div class="theme-log-meta-item">
                        <dt>{{ t("activity.date_time") }}</dt>
                        <dd>{{ formatDate(activity.created_at) }}</dd>
                    </div>
                </dl>

                <div class="theme-log-section">
                    <h3 class="theme-log-section-title">{{ t("activity.changes") }}</h3>

                    <div v-if="hasChangeDetails(activity)" class="theme-change-list">
                        <div
                            v-for="change in changes"
                            :key="change.field"
                            class="theme-change-block"
                        >
                            <p class="theme-change-label">{{ change.label }}</p>

                            <div
                                v-if="change.type === 'updated'"
                                class="theme-change-values"
                            >
                                <span class="theme-change-old">
                                    {{ formatValue(change.old) }}
                                </span>
                                <svg
                                    class="theme-change-arrow"
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
                                <span class="theme-change-new">
                                    {{ formatValue(change.new) }}
                                </span>
                            </div>

                            <div v-else-if="change.type === 'created'">
                                <span class="theme-change-new">
                                    {{ formatValue(change.new) }}
                                </span>
                            </div>

                            <div v-else>
                                <span class="theme-change-old">
                                    {{ formatValue(change.old) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <p v-else class="theme-log-empty">
                        {{ t("activity.no_detail_long") }}
                    </p>
                </div>

                <dl class="theme-log-meta theme-log-meta-footer">
                    <div class="theme-log-meta-item">
                        <dt>{{ t("activity.id") }}</dt>
                        <dd>#{{ activity.id }}</dd>
                    </div>
                    <div class="theme-log-meta-item">
                        <dt>{{ t("activity.log_name") }}</dt>
                        <dd class="capitalize">{{ activity.log_name || "—" }}</dd>
                    </div>
                    <div class="theme-log-meta-item">
                        <dt>{{ t("activity.event") }}</dt>
                        <dd class="capitalize">
                            {{ eventLabel(activity.event || actionKey) }}
                        </dd>
                    </div>
                    <div class="theme-log-meta-item">
                        <dt>{{ t("activity.causer_id") }}</dt>
                        <dd>{{ activity.causer_id || "—" }}</dd>
                    </div>
                    <div class="theme-log-meta-item">
                        <dt>{{ t("activity.subject_type") }}</dt>
                        <dd>{{ activity.subject_type || "—" }}</dd>
                    </div>
                    <div class="theme-log-meta-item">
                        <dt>{{ t("activity.batch") }}</dt>
                        <dd class="theme-log-meta-truncate">
                            {{ activity.batch_uuid || "—" }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
