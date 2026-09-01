<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed, onMounted, nextTick } from "vue";
import AOS from "aos";
import "aos/dist/aos.css";
import { usePermissions } from "@/composables/usePermissions";
import { useI18n } from "@/composables/useI18n";

const { can, isSuperAdmin } = usePermissions();
const { t } = useI18n();

onMounted(() => {
    nextTick(() => {
        AOS.init({
            duration: 800,
            once: true,
            mirror: false,
        });
    });
});

const stats = computed(() => [
    {
        title: t("dashboard.open_jobs"),
        value: "0",
        iconPath: "M13 7h8m0 0v8m0-8l-8 8-4-4-6 6",
    },
    {
        title: t("dashboard.in_review"),
        value: "0",
        iconPath: "M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z",
    },
    {
        title: t("dashboard.trainings"),
        value: "0",
        iconPath: "M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z",
    },
]);

const allQuickActions = computed(() => [
    {
        title: t("dashboard.manage_users"),
        description: t("dashboard.manage_users_hint"),
        route: "users.index",
        permission: "view user",
        iconPath:
            "M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z",
    },
    {
        title: t("dashboard.manage_roles"),
        description: t("dashboard.manage_roles_hint"),
        route: "roles.index",
        permission: "view role",
        iconPath:
            "M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z",
    },
    {
        title: t("dashboard.activity"),
        description: t("dashboard.activity_hint"),
        route: "activity.index",
        permission: null,
        superadminOnly: true,
        iconPath: "M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z",
    },
    {
        title: t("dashboard.profile"),
        description: t("dashboard.profile_hint"),
        route: "profile.edit",
        permission: null,
        iconPath:
            "M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z",
    },
]);

const quickActions = computed(() =>
    allQuickActions.value.filter((action) => {
        if (action.superadminOnly && !isSuperAdmin.value) {
            return false;
        }

        return !action.permission || can(action.permission);
    }),
);

</script>

<template>
    <Head :title="t('dashboard.title')" />

    <AuthenticatedLayout>
        <div class="space-y-4 lg:space-y-5">
            <div data-aos="fade-down" class="theme-welcome">
                <h2 class="theme-welcome-title">
                    {{ t("dashboard.overview") }}
                </h2>
                <p class="theme-welcome-text">
                    {{ t("dashboard.overview_text") }}
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="(stat, index) in stats"
                    :key="stat.title"
                    data-aos="fade-up"
                    :data-aos-delay="index * 80"
                    class="theme-stat-card"
                >
                    <div>
                        <p class="theme-stat-label">{{ stat.title }}</p>
                        <p class="theme-stat-value">{{ stat.value }}</p>
                    </div>
                    <div class="theme-stat-icon">
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                :d="stat.iconPath"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <section data-aos="fade-up" class="theme-form-card overflow-hidden">
                <div
                    class="theme-form-section-header flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between"
                >
                    <div>
                        <h3 class="theme-form-section-title">
                            {{ t("dashboard.quick_actions") }}
                        </h3>
                        <p class="theme-text-muted mt-1 text-xs">
                            {{ t("dashboard.quick_actions_hint") }}
                        </p>
                    </div>
                    <span class="theme-badge">
                        {{
                            t("dashboard.shortcuts", {
                                count: quickActions.length,
                            })
                        }}
                    </span>
                </div>

                <div class="divide-y" style="border-color: var(--color-border)">
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3"
                    >
                        <Link
                            v-for="action in quickActions"
                            :key="action.route"
                            :href="route(action.route)"
                            class="theme-quick-action"
                        >
                            <div class="theme-quick-action-icon">
                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        :d="action.iconPath"
                                    />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="theme-heading truncate text-sm font-bold">
                                    {{ action.title }}
                                </h4>
                                <p class="theme-text-muted mt-0.5 line-clamp-2 text-xs leading-relaxed">
                                    {{ action.description }}
                                </p>
                            </div>
                        </Link>
                    </div>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
