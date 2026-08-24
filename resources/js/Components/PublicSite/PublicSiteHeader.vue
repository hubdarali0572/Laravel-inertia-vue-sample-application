<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed, onMounted, onUnmounted, ref, watch } from "vue";
import InstitutionLogo from "@/Components/PublicSite/InstitutionLogo.vue";
import PublicSiteNavLink from "@/Components/PublicSite/PublicSiteNavLink.vue";

const page = usePage();
const cms = computed(() => page.props.institution || {});
const name = computed(() => cms.value.name || "Bright Future Educational Institute");
const studentPortalUrl = computed(() => cms.value.student_portal_url || "/login");
const lmsUrl = computed(() => cms.value.lms_url || "/login");

const isMobileMenuOpen = ref(false);
const isDesktop = ref(false);

const navItems = [
    { label: "Home", href: route("publicSite.home"), routeName: "publicSite.home" },
    { label: "About", href: route("publicSite.about"), routeName: "publicSite.about" },
    { label: "Academics", href: route("publicSite.academics"), routeName: "publicSite.academics" },
    { label: "Admissions", href: route("publicSite.admissions"), routeName: "publicSite.admissions" },
    { label: "Campus Life", href: route("publicSite.campus"), routeName: "publicSite.campus" },
    { label: "News & Events", href: route("publicSite.news"), routeName: "publicSite.news" },
    { label: "Contact", href: route("publicSite.contact"), routeName: "publicSite.contact" },
];

const isActive = (item) => route().current(item.routeName);

const closeMobileMenu = () => {
    isMobileMenuOpen.value = false;
};

let mediaQuery = null;

const syncViewport = () => {
    isDesktop.value = mediaQuery ? mediaQuery.matches : false;
    if (isDesktop.value) {
        closeMobileMenu();
    }
};

watch(
    () => page.url,
    () => closeMobileMenu(),
);

watch(isMobileMenuOpen, (open) => {
    document.body.style.overflow = open ? "hidden" : "";
});

onMounted(() => {
    mediaQuery = window.matchMedia("(min-width: 1024px)");
    syncViewport();
    mediaQuery.addEventListener("change", syncViewport);
});

onUnmounted(() => {
    if (mediaQuery) {
        mediaQuery.removeEventListener("change", syncViewport);
    }
    document.body.style.removeProperty("overflow");
});
</script>

<template>
    <div class="ps-chrome">
        <header class="ps-header">
            <nav class="ps-container ps-header-inner" aria-label="Primary">
                <Link
                    :href="route('publicSite.home')"
                    class="ps-brand"
                    :aria-label="name"
                    @click="closeMobileMenu"
                >
                    <span class="ps-brand-mark">
                        <InstitutionLogo />
                    </span>
                </Link>

                <div v-if="isDesktop" class="ps-nav">
                    <PublicSiteNavLink
                        v-for="item in navItems"
                        :key="item.routeName"
                        :href="item.href"
                        :active="isActive(item)"
                    >
                        {{ item.label }}
                    </PublicSiteNavLink>
                </div>

                <div class="ps-header-actions">
                    <a
                        v-if="isDesktop"
                        :href="studentPortalUrl"
                        class="ps-util-link"
                    >
                        Student Portal
                    </a>
                    <a v-if="isDesktop" :href="lmsUrl" class="ps-util-link">LMS</a>
                    <Link :href="route('publicSite.admissions')" class="ps-btn ps-btn-primary">
                        Apply Now
                    </Link>
                    <button
                        v-if="!isDesktop"
                        type="button"
                        class="ps-menu-btn"
                        :aria-expanded="isMobileMenuOpen"
                        aria-controls="public-mobile-menu"
                        :aria-label="isMobileMenuOpen ? 'Close menu' : 'Open menu'"
                        @click="isMobileMenuOpen = !isMobileMenuOpen"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                :d="isMobileMenuOpen ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16'"
                            />
                        </svg>
                    </button>
                </div>
            </nav>

            <div
                v-if="!isDesktop && isMobileMenuOpen"
                id="public-mobile-menu"
                class="ps-mobile-panel"
            >
                <div class="ps-container" style="padding-top: 0.75rem; padding-bottom: 1.25rem">
                    <PublicSiteNavLink
                        v-for="item in navItems"
                        :key="`m-${item.routeName}`"
                        :href="item.href"
                        :active="isActive(item)"
                        mobile
                        @navigate="closeMobileMenu"
                    >
                        {{ item.label }}
                    </PublicSiteNavLink>
                    <a
                        :href="studentPortalUrl"
                        class="ps-mobile-link"
                        @click="closeMobileMenu"
                    >
                        Student Portal
                    </a>
                    <a :href="lmsUrl" class="ps-mobile-link" @click="closeMobileMenu">LMS</a>
                </div>
            </div>
        </header>
    </div>

    <div
        v-if="!isDesktop && isMobileMenuOpen"
        class="ps-overlay"
        aria-hidden="true"
        @click="closeMobileMenu"
    />
</template>
