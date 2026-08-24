<script setup>
import PublicBreadcrumb from "@/Components/PublicSite/PublicBreadcrumb.vue";
import PublicIcon from "@/Components/PublicSite/PublicIcon.vue";

defineProps({
    image: { type: String, required: true },
    alt: { type: String, default: "" },
    kicker: { type: String, default: "" },
    title: { type: String, required: true },
    description: { type: String, default: "" },
    crumbs: { type: Array, default: () => [] },
    facts: { type: Array, default: () => [] },
    objectPosition: { type: String, default: "center center" },
});
</script>

<template>
    <section class="ps-hero" :style="{ backgroundImage: `url('${image}')` }">
        <img
            class="ps-hero-media"
            :src="image"
            :alt="alt || title"
            :style="{ objectPosition }"
        />
        <div class="ps-hero-overlay" />
        <div class="ps-container">
            <div class="ps-hero-content" data-aos="fade-up">
                <PublicBreadcrumb v-if="crumbs.length" :items="crumbs" />
                <p v-if="kicker" class="ps-kicker">{{ kicker }}</p>
                <h1 class="ps-h1">{{ title }}</h1>
                <p v-if="description" class="ps-lead">{{ description }}</p>
                <div v-if="facts.length" class="ps-hero-facts">
                    <span v-for="fact in facts" :key="fact.label" class="ps-hero-fact">
                        <PublicIcon v-if="fact.icon" :name="fact.icon" />
                        {{ fact.label }}
                    </span>
                </div>
                <div v-if="$slots.actions" class="ps-actions">
                    <slot name="actions" />
                </div>
            </div>
        </div>
    </section>
</template>
