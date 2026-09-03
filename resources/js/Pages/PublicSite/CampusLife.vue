<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import PublicSiteLayout from "@/Layouts/PublicSite/PublicSiteLayout.vue";
import PublicPageHero from "@/Components/PublicSite/PublicPageHero.vue";
import PublicModal from "@/Components/PublicSite/PublicModal.vue";
import PublicIcon from "@/Components/PublicSite/PublicIcon.vue";

const cms = computed(() => {
    const value = usePage().props.institution;
    return value && typeof value === "object" && !Array.isArray(value) ? value : {};
});
const type = computed(() => {
    const value = String(cms.value.type || "university").toLowerCase();
    return ["school", "college", "university"].includes(value) ? value : "university";
});
const typeLabel = computed(
    () => ({ school: "School", college: "College", university: "University" })[type.value],
);

const images = {
    hero: "/images/campus-life.jpg",
    classroom: "/images/campus/classroom.jpg",
    library: "/images/campus/library.jpg",
    computer: "/images/campus/computer.jpg",
    science: "/images/campus/science.jpg",
    sports: "/images/campus/sports.jpg",
    auditorium: "/images/campus/auditorium.jpg",
    students: "/images/campus/students.jpg",
    cafeteria: "/images/campus/cafeteria.jpg",
    transport: "/images/campus/transport.jpg",
    medical: "/images/campus/medical.jpg",
    events: "/images/campus/events.jpg",
    campus: "/images/campus-life.jpg",
    homeHero: "/images/hero-campus.jpg",
    academics: "/images/hero-academics.jpg",
    admissions: "/images/hero-admissions.jpg",
    about: "/images/hero-about.jpg",
    news: "/images/hero-news.jpg",
};

const tab = ref("All");
const lightbox = ref(null);
const categories = ["All", "Campus", "Classrooms", "Laboratories", "Sports", "Events", "Students"];

const facilities = [
    { name: "Smart Classrooms", icon: "monitor", body: "Digitally enabled rooms that support interactive teaching and presentation.", image: images.classroom },
    { name: "Computer Labs", icon: "cog", body: "Computing laboratories with networked workstations for practical classes.", image: images.computer },
    { name: "Science Laboratories", icon: "flask", body: "Biology, chemistry, and physics labs maintained for safe experimental work.", image: images.science },
    { name: "Central Library", icon: "book", body: "A quiet study environment with print collections and reading spaces.", image: images.library },
    { name: "Auditorium", icon: "users", body: "A formal venue for seminars, orientations, and institutional events.", image: images.auditorium },
    { name: "Sports Complex", icon: "trophy", body: "Indoor and outdoor facilities for games, training, and the annual sports gala.", image: images.sports },
    { name: "Cafeteria", icon: "cafe", body: "Hygienic dining spaces serving students and staff throughout the day.", image: images.cafeteria },
    { name: "Transportation", icon: "bus", body: "Campus transport routes covering selected areas of Lahore.", image: images.transport },
    { name: "Medical Center", icon: "heart", body: "First-aid and basic health support during campus hours.", image: images.medical },
];

const sports = [
    { name: "Cricket", icon: "trophy", note: "Turf practice nets and inter-house fixtures." },
    { name: "Football", icon: "users", note: "Ground training and campus league matches." },
    { name: "Basketball", icon: "star", note: "Indoor court with regular coaching sessions." },
    { name: "Badminton", icon: "bolt", note: "Indoor courts for students and staff." },
    { name: "Athletics", icon: "chart", note: "Track events during the annual sports gala." },
    { name: "Table Tennis", icon: "bell", note: "Recreation rooms open throughout the week." },
];

const support = [
    { title: "Academic Counseling", icon: "book", body: "Guidance on subject selection, study habits, and academic recovery plans." },
    { title: "Career Counseling", icon: "briefcase", body: "University placement, career mapping, and interview preparation support." },
    { title: "Student Affairs", icon: "building", body: "A dedicated office for student welfare, discipline, and campus engagement." },
    { title: "Health Services", icon: "heart", body: "On-campus medical support for first aid and student wellbeing." },
    { title: "Scholarships", icon: "star", body: "Advice on merit, need-based, and talent scholarship applications." },
    { title: "Career Development", icon: "chart", body: "Workshops, internships, and skills programmes that prepare students for the next stage." },
];

const gallery = [
    { src: images.campus, alt: "Main campus building", category: "Campus" },
    { src: images.classroom, alt: "Smart classroom", category: "Classrooms" },
    { src: images.science, alt: "Science laboratory", category: "Laboratories" },
    { src: images.computer, alt: "Computer laboratory", category: "Laboratories" },
    { src: images.sports, alt: "Sports field", category: "Sports" },
    { src: images.events, alt: "Campus event", category: "Events" },
    { src: images.students, alt: "Students on campus", category: "Students" },
    { src: images.library, alt: "Central library", category: "Campus" },
    { src: images.auditorium, alt: "Auditorium", category: "Events" },
    { src: images.homeHero, alt: "Campus lawn", category: "Campus" },
    { src: images.academics, alt: "Lecture in progress", category: "Classrooms" },
    { src: images.cafeteria, alt: "Campus cafeteria", category: "Campus" },
    { src: images.transport, alt: "Campus transport", category: "Campus" },
    { src: images.medical, alt: "Medical centre", category: "Campus" },
    { src: images.admissions, alt: "Student gathering", category: "Students" },
    { src: images.about, alt: "Campus community", category: "Students" },
    { src: images.news, alt: "Sports and recreation", category: "Sports" },
];

const studentLife = computed(() => {
    const items = [
        { title: "Student Clubs", icon: "users", body: "Academic, cultural, and service clubs that run throughout the year." },
        { title: "Societies", icon: "building", body: "Debating, science, literary, and community societies led by students and faculty advisors." },
        { title: "Competitions", icon: "trophy", body: "Inter-house and inter-institutional contests in academics, sports, and the arts." },
        { title: "Workshops", icon: "cog", body: "Skills workshops in communication, computing, and study methods." },
        { title: "Seminars", icon: "quote", body: "Guest lectures and subject seminars that connect classroom learning with practice." },
        { title: "Cultural Activities", icon: "swatch", body: "Annual day, national celebrations, and student performances." },
        { title: "Community Service", icon: "heart", body: "Structured volunteer activities that develop civic responsibility." },
    ];

    if (type.value === "school") {
        items.push(
            { title: "House System", icon: "star", body: "Students belong to houses that foster teamwork, healthy competition, and pastoral identity." },
            { title: "Parent-Teacher Meetings", icon: "clipboard", body: "Scheduled conferences so families can review progress and next steps with teachers." },
        );
    }

    if (type.value !== "school") {
        items.push(
            { title: "Internship Support", icon: "briefcase", body: "Guidance for internships and workplace exposure relevant to each programme." },
            { title: "Student Council", icon: "cap", body: "Elected student representatives who voice campus concerns and organise peer activities." },
        );
    }

    return items;
});

const highlights = computed(() => {
    if (type.value === "school") {
        return [
            { title: "Parent Services", icon: "users", body: "A parent liaison desk for transport, meetings, and academic queries." },
            { title: "School Transport", icon: "bus", body: "Supervised bus routes with published pickup points across Lahore." },
            { title: "Homework / LMS", icon: "monitor", body: "Daily assignments and resources published through the learning portal." },
            { title: "Student Attendance", icon: "clipboard", body: "Attendance is recorded daily and shared with parents through the portal." },
        ];
    }

    if (type.value === "college") {
        return [
            { title: "Career Counseling", icon: "briefcase", body: "University admission guidance and subject counselling for intermediate students." },
            { title: "Examination Support", icon: "clipboard", body: "Board examination preparation, mock assessments, and result advising." },
            { title: "Alumni Network", icon: "users", body: "Former students return for mentoring sessions and career talks." },
            { title: "Student Portal", icon: "monitor", body: "Timetables, attendance, and notices available through the college learning portal." },
        ];
    }

    return [
        { title: "Student Affairs", icon: "building", body: "A central office for student welfare, societies, and campus conduct." },
        { title: "Career Development", icon: "briefcase", body: "Employability workshops, CV clinics, and employer engagement days." },
        { title: "Hostel Life", icon: "cafe", body: "Residential students follow a structured routine with warden support." },
        { title: "ORIC & Research Culture", icon: "flask", body: "Opportunities to join faculty-led projects and present at student research forums." },
    ];
});

const visibleGallery = computed(() =>
    tab.value === "All" ? gallery : gallery.filter((item) => item.category === tab.value),
);
</script>

<template>
    <PublicSiteLayout>
        <Head title="Campus Life">
            <meta
                head-key="description"
                name="description"
                content="Discover campus facilities, student clubs, sports, support services, and gallery from Bright Future Educational Institute."
            />
            <link head-key="canonical" rel="canonical" :href="route('publicSite.campus')" />
        </Head>

        <PublicPageHero
            image="/images/hero-campus.jpg"
            object-position="center 42%"
            alt="Campus life at Bright Future Educational Institute"
            kicker="Student Experience"
            title="Experience Life Beyond the Classroom"
            description="Our campus is designed for learning, collaboration, sport, creativity, and personal development — with facilities, societies, and support that sit alongside academic study."
            :crumbs="[{ label: 'Campus Life' }]"
            :facts="[
                { icon: 'building', label: 'Modern Facilities' },
                { icon: 'trophy', label: 'Sports & Societies' },
                { icon: 'users', label: 'Student Support' },
                { icon: 'star', label: 'Community Life' },
            ]"
        />

        <section class="ps-section">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">Facilities</h2>
                    <p class="ps-lead">Nine purpose-built spaces that support teaching, research, sport, dining, transport, and student wellbeing.</p>
                </div>
                <div class="ps-facilities-grid">
                    <article
                        v-for="(item, index) in facilities"
                        :key="item.name"
                        class="ps-facility-card"
                        data-aos="fade-up"
                        :data-aos-delay="(index % 3) * 80"
                    >
                        <img :src="item.image" :alt="item.name" />
                        <span class="ps-facility-shade" aria-hidden="true" />
                        <span class="ps-facility-icon" aria-hidden="true">
                            <PublicIcon :name="item.icon" />
                        </span>
                        <span class="ps-facility-copy">
                            <span class="ps-facility-name">{{ item.name }}</span>
                            <span class="ps-facility-note">{{ item.body }}</span>
                        </span>
                    </article>
                </div>
            </div>
        </section>

        <section class="ps-section ps-section-muted">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">Student Life</h2>
                    <p class="ps-lead">Clubs, societies, and campus activities that sit alongside academic study.</p>
                </div>
                <div class="ps-grid ps-grid-3">
                    <article
                        v-for="(item, index) in studentLife"
                        :key="item.title"
                        class="ps-unit-card"
                        data-aos="fade-up"
                        :data-aos-delay="(index % 3) * 80"
                    >
                        <span class="ps-unit-card-accent" aria-hidden="true" />
                        <div class="ps-unit-card-top">
                            <span class="ps-unit-icon" aria-hidden="true">
                                <PublicIcon :name="item.icon" />
                            </span>
                            <span class="ps-unit-index">{{ String(index + 1).padStart(2, "0") }}</span>
                        </div>
                        <h3 class="ps-unit-name">{{ item.title }}</h3>
                        <p class="ps-unit-copy">{{ item.body }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="ps-section">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">Sports</h2>
                    <p class="ps-lead">Indoor and outdoor sport with regular coaching, fixtures, and the annual sports gala.</p>
                </div>
                <div class="ps-grid ps-grid-3">
                    <article
                        v-for="(item, index) in sports"
                        :key="item.name"
                        class="ps-unit-card"
                        data-aos="fade-up"
                        :data-aos-delay="(index % 3) * 80"
                    >
                        <span class="ps-unit-card-accent" aria-hidden="true" />
                        <div class="ps-unit-card-top">
                            <span class="ps-unit-icon" aria-hidden="true">
                                <PublicIcon :name="item.icon" />
                            </span>
                            <span class="ps-unit-index">{{ String(index + 1).padStart(2, "0") }}</span>
                        </div>
                        <h3 class="ps-unit-name">{{ item.name }}</h3>
                        <p class="ps-unit-copy">{{ item.note }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="ps-section ps-section-muted">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">Student Support</h2>
                    <p class="ps-lead">Academic, career, health, and welfare services available throughout the session.</p>
                </div>
                <div class="ps-grid ps-grid-3">
                    <article
                        v-for="(item, index) in support"
                        :key="item.title"
                        class="ps-unit-card"
                        data-aos="fade-up"
                        :data-aos-delay="(index % 3) * 80"
                    >
                        <span class="ps-unit-card-accent" aria-hidden="true" />
                        <div class="ps-unit-card-top">
                            <span class="ps-unit-icon" aria-hidden="true">
                                <PublicIcon :name="item.icon" />
                            </span>
                            <span class="ps-unit-index">{{ String(index + 1).padStart(2, "0") }}</span>
                        </div>
                        <h3 class="ps-unit-name">{{ item.title }}</h3>
                        <p class="ps-unit-copy">{{ item.body }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section v-if="highlights.length" class="ps-section">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">{{ typeLabel }} Campus Services</h2>
                    <p class="ps-lead">Everyday campus services that keep students, families, and academic life running smoothly.</p>
                </div>
                <div class="ps-grid ps-grid-2">
                    <article
                        v-for="(item, index) in highlights"
                        :key="item.title"
                        class="ps-unit-card ps-unit-card-wide"
                        data-aos="fade-up"
                        :data-aos-delay="(index % 2) * 80"
                    >
                        <span class="ps-unit-card-accent" aria-hidden="true" />
                        <span class="ps-unit-icon" aria-hidden="true">
                            <PublicIcon :name="item.icon" />
                        </span>
                        <div class="ps-unit-wide-copy">
                            <div class="ps-unit-wide-head">
                                <h3 class="ps-unit-name">{{ item.title }}</h3>
                                <span class="ps-unit-index">{{ String(index + 1).padStart(2, "0") }}</span>
                            </div>
                            <p class="ps-unit-copy">{{ item.body }}</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="ps-section ps-section-muted">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">Gallery</h2>
                    <p class="ps-lead">A look at campus spaces, student life, laboratories, sport, and events.</p>
                </div>
                <div class="ps-tabs" role="tablist">
                    <button
                        v-for="item in categories"
                        :key="item"
                        type="button"
                        class="ps-tab"
                        :class="{ 'is-active': tab === item }"
                        @click="tab = item"
                    >
                        {{ item }}
                    </button>
                </div>
                <div v-if="visibleGallery.length" class="ps-gallery">
                    <button
                        v-for="(item, index) in visibleGallery"
                        :key="item.src + item.alt"
                        type="button"
                        class="ps-gallery-item"
                        :class="{ 'is-featured': index === 0 }"
                        data-aos="fade-up"
                        :data-aos-delay="(index % 4) * 70"
                        @click="lightbox = item"
                    >
                        <img :src="item.src" :alt="item.alt" />
                        <span class="ps-gallery-shade" aria-hidden="true" />
                        <span class="ps-gallery-caption">
                            <span class="ps-gallery-label">{{ item.category }}</span>
                            <span class="ps-gallery-name">{{ item.alt }}</span>
                        </span>
                    </button>
                </div>
                <p v-else class="ps-empty">No images in this category yet.</p>
            </div>
        </section>

        <PublicModal :open="Boolean(lightbox)" :title="lightbox?.alt || 'Gallery'" @close="lightbox = null">
            <img v-if="lightbox" class="ps-lightbox-img" :src="lightbox.src" :alt="lightbox.alt" />
        </PublicModal>
    </PublicSiteLayout>
</template>
