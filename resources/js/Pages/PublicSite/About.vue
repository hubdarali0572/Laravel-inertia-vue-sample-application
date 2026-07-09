<script setup>
import { Head } from "@inertiajs/vue3";
import PublicSiteLayout from "@/Layouts/PublicSite/PublicSiteLayout.vue";
</script>

<template>
    <PublicSiteLayout>
        <Head title="About" />

        <section class="py-20 lg:py-28">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-3xl" data-aos="fade-up">
                    <p
                        class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-600 dark:text-indigo-400"
                    >
                        About Us
                    </p>
                    <h1
                        class="mt-3 text-4xl font-extrabold tracking-tight sm:text-5xl"
                    >
                        Building unified digital governance
                    </h1>
                    <p
                        class="mt-6 text-lg leading-8 text-slate-600 dark:text-slate-300"
                    >
                        Unified Media is a production-ready platform for teams
                        that need secure user management, rich media handling,
                        and complete operational transparency in one cohesive
                        system.
                    </p>
                </div>

                <div
                    class="mx-auto mt-16 grid max-w-5xl grid-cols-1 gap-8 md:grid-cols-2"
                >
                    <article
                        data-aos="fade-up"
                        class="rounded-2xl bg-white p-8 ring-1 ring-slate-200 shadow-sm dark:bg-slate-900/60 dark:ring-slate-800"
                    >
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">
                            Our Mission
                        </h2>
                        <p
                            class="mt-3 text-sm leading-relaxed text-slate-600 dark:text-slate-400"
                        >
                            Deliver a starter ecosystem that removes ambiguity
                            from architecture decisions so teams can focus on
                            product value instead of rebuilding foundations.
                        </p>
                    </article>

                    <article
                        data-aos="fade-up"
                        data-aos-delay="100"
                        class="rounded-2xl bg-white p-8 ring-1 ring-slate-200 shadow-sm dark:bg-slate-900/60 dark:ring-slate-800"
                    >
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">
                            What We Provide
                        </h2>
                        <p
                            class="mt-3 text-sm leading-relaxed text-slate-600 dark:text-slate-400"
                        >
                            Role-based access control, media libraries, activity
                            logging, security hardening, and automated backups —
                            all integrated and ready to extend.
                        </p>
                    </article>
                </div>
            </div>
        </section>
    </PublicSiteLayout>
</template>
