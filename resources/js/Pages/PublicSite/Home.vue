<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { computed, onMounted, onUnmounted, ref, watch } from "vue";
import PublicSiteLayout from "@/Layouts/PublicSite/PublicSiteLayout.vue";
import PublicIcon from "@/Components/PublicSite/PublicIcon.vue";

const cms = computed(() => {
    const value = usePage().props.institution;
    return value && typeof value === "object" && !Array.isArray(value)
        ? value
        : {};
});
const type = computed(() => {
    const value = String(cms.value.type || "university").toLowerCase();
    return ["school", "college", "university"].includes(value)
        ? value
        : "university";
});
const typeLabel = computed(
    () =>
        ({ school: "School", college: "College", university: "University" })[
            type.value
        ],
);

const images = {
    hero: "/images/hero-home.avif",
    classroom:
        "https://images.unsplash.com/photo-1580582932707-520aed937b7b?auto=format&fit=crop&w=1200&q=80",
    library:
        "https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=1200&q=80",
    computer:
        "https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=80",
    science:
        "https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&w=1200&q=80",
    sports: "https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=1200&q=80",
    auditorium:
        "https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?auto=format&fit=crop&w=1200&q=80",
    students: "/images/campus-life.jpg",
    cafeteria:
        "https://images.unsplash.com/photo-1567521464027-f127ff144326?auto=format&fit=crop&w=1200&q=80",
    medical:
        "https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=1200&q=80",
    hostel:
        "https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=1200&q=80",
    leader: "https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=800&q=80",
};

const statisticsByType = {
    school: [
        { value: 1800, suffix: "+", label: "Students" },
        { value: 120, suffix: "+", label: "Faculty Members" },
        { value: 12, suffix: "+", label: "Academic Programs" },
    ],
    college: [
        { value: 3200, suffix: "+", label: "Students" },
        { value: 160, suffix: "+", label: "Faculty Members" },
        { value: 18, suffix: "+", label: "Academic Programs" },
    ],
    university: [
        { value: 5000, suffix: "+", label: "Students" },
        { value: 250, suffix: "+", label: "Faculty Members" },
        { value: 30, suffix: "+", label: "Academic Programs" },
        { value: 10000, suffix: "+", label: "Alumni" },
    ],
};

const programCatalog = {
    school: [
        {
            id: "early-years",
            name: "Early Years",
            level: "Foundation",
            duration: "2 years",
            description:
                "A nurturing introduction to school life focusing on language, play, motor skills, and social development.",
        },
        {
            id: "primary",
            name: "Primary",
            level: "Primary",
            duration: "5 years",
            description:
                "A broad primary curriculum covering literacy, numeracy, science, arts, and personal development.",
        },
        {
            id: "middle",
            name: "Middle School",
            level: "Middle",
            duration: "3 years",
            description:
                "Specialist subject teaching that strengthens independent study and prepares students for secondary school.",
        },
        {
            id: "secondary",
            name: "Secondary School",
            level: "Secondary",
            duration: "2 years",
            description:
                "Secondary education with a focus on core subjects, examination habits, and responsible citizenship.",
        },
        {
            id: "olevel",
            name: "O-Level",
            level: "O-Level",
            duration: "2 years",
            description:
                "Cambridge O-Level pathway with structured subject options and regular assessment.",
        },
        {
            id: "alevel",
            name: "A-Level",
            level: "A-Level",
            duration: "2 years",
            description:
                "Advanced Level study for university entry, with guidance on subject combinations and applications.",
        },
    ],
    college: [
        {
            id: "pre-medical",
            name: "FSc Pre-Medical",
            level: "Intermediate",
            duration: "2 years",
            description:
                "Biology, chemistry, and physics for medical and allied health university pathways.",
        },
        {
            id: "pre-engineering",
            name: "FSc Pre-Engineering",
            level: "Intermediate",
            duration: "2 years",
            description:
                "Mathematics, physics, and chemistry for engineering and technology programmes.",
        },
        {
            id: "ics",
            name: "ICS",
            level: "Intermediate",
            duration: "2 years",
            description:
                "Intermediate Computer Science combining programming foundations with mathematics and physics or statistics.",
        },
        {
            id: "icom",
            name: "I.Com",
            level: "Intermediate",
            duration: "2 years",
            description:
                "Accounting, commerce, and economics for business and professional accounting routes.",
        },
        {
            id: "adp-cs",
            name: "Associate Degree in Computer Science",
            level: "Associate Degree",
            duration: "2 years",
            description:
                "A two-year associate degree building applied computing skills for work or further study.",
        },
        {
            id: "adp-ba",
            name: "Associate Degree in Business",
            level: "Associate Degree",
            duration: "2 years",
            description:
                "Foundations of management, accounting, and entrepreneurship at associate degree level.",
        },
    ],
    university: [
        {
            id: "bscs",
            name: "BS Computer Science",
            level: "Undergraduate",
            duration: "4 years",
            description:
                "A rigorous computing degree covering programming, algorithms, databases, and software systems.",
        },
        {
            id: "bsse",
            name: "BS Software Engineering",
            level: "Undergraduate",
            duration: "4 years",
            description:
                "Software process, design, quality, and team-based development for professional engineering practice.",
        },
        {
            id: "bsit",
            name: "BS Information Technology",
            level: "Undergraduate",
            duration: "4 years",
            description:
                "Applied IT systems, networks, and digital services for organizational environments.",
        },
        {
            id: "bba",
            name: "BBA",
            level: "Undergraduate",
            duration: "4 years",
            description:
                "Business administration with pathways in marketing, finance, and management.",
        },
        {
            id: "bsmath",
            name: "BS Mathematics",
            level: "Undergraduate",
            duration: "4 years",
            description:
                "Pure and applied mathematics preparing students for teaching, analysis, and graduate study.",
        },
        {
            id: "mba",
            name: "MBA",
            level: "Graduate",
            duration: "2 years",
            description:
                "A professional master’s programme for leadership, strategy, and managerial decision-making.",
        },
    ],
};

const leadersByType = {
    school: {
        name: "Dr. Ahmed Khan",
        designation: "Principal",
        photo: images.leader,
    },
    college: {
        name: "Dr. Ahmed Khan",
        designation: "Principal",
        photo: images.leader,
    },
    university: {
        name: "Dr. Ahmed Khan",
        designation: "Vice Chancellor",
        photo: images.leader,
    },
};

const programs = computed(() => {
    const items =
        cms.value.programs ||
        programCatalog[type.value] ||
        programCatalog.university;
    return items.slice(0, type.value === "university" ? 8 : 6);
});
const statistics = computed(
    () => statisticsByType[type.value] || statisticsByType.university,
);
const leader = computed(
    () => leadersByType[type.value] || leadersByType.university,
);

const whyItems = [
    {
        icon: "cap",
        title: "Qualified Faculty",
        body: "Experienced educators and subject specialists committed to high academic standards and student mentoring.",
    },
    {
        icon: "building",
        title: "Modern Learning Environment",
        body: "Purpose-built classrooms, collaborative spaces, and campus facilities that support focused study.",
    },
    {
        icon: "flask",
        title: "Advanced Laboratories",
        body: "Well-equipped science, computing, and practical labs that turn theory into applied learning.",
    },
    {
        icon: "monitor",
        title: "Digital Learning",
        body: "A learning management system, smart classrooms, and digital resources that extend study beyond campus hours.",
    },
    {
        icon: "trophy",
        title: "Student Development",
        body: "Clubs, sports, leadership opportunities, and counselling that develop character alongside academic ability.",
    },
    {
        icon: "briefcase",
        title: "Career & Academic Support",
        body: "Dedicated guidance for examinations, higher education pathways, internships, and career planning.",
    },
];

const news = [
    {
        title: "Annual Sports Gala 2026",
        date: "12 March 2026",
        category: "Campus",
        icon: "trophy",
        image: images.sports,
        excerpt:
            "Students participated in football, cricket, athletics, badminton, and other sporting activities.",
    },
    {
        title: "Science Exhibition 2026",
        date: "18 February 2026",
        category: "Academics",
        icon: "flask",
        image: images.science,
        excerpt:
            "Learners presented laboratory projects, models, and investigative posters to visitors and judges.",
    },
    {
        title: "New Academic Session Announced",
        date: "01 August 2026",
        category: "Admissions",
        icon: "clipboard",
        image: images.students,
        excerpt:
            "The Institute has announced admissions and key dates for academic session 2026–27.",
    },
];

const events = [
    {
        date: "05 September 2026",
        day: "05",
        month: "Sep",
        title: "Orientation Day",
        place: "Main Auditorium",
        icon: "users",
    },
    {
        date: "15 September 2026",
        day: "15",
        month: "Sep",
        title: "Annual Sports Gala",
        place: "Sports Complex",
        icon: "trophy",
    },
    {
        date: "20 September 2026",
        day: "20",
        month: "Sep",
        title: "Parent-Teacher Meeting",
        place: "Academic Block",
        icon: "users",
    },
];

const facilities = [
    {
        name: "Smart Classrooms",
        image: images.classroom,
        icon: "monitor",
        note: "Interactive teaching spaces",
    },
    {
        name: "Library",
        image: images.library,
        icon: "book",
        note: "Print and digital collections",
    },
    {
        name: "Computer Labs",
        image: images.computer,
        icon: "monitor",
        note: "Networked practical computing",
    },
    {
        name: "Science Labs",
        image: images.science,
        icon: "flask",
        note: "Biology, chemistry, and physics",
    },
    {
        name: "Sports Complex",
        image: images.sports,
        icon: "trophy",
        note: "Indoor and outdoor training",
    },
    {
        name: "Auditorium",
        image: images.auditorium,
        icon: "users",
        note: "Seminars and formal events",
    },
    {
        name: "Cafeteria",
        image: images.cafeteria,
        icon: "cafe",
        note: "Dining spaces for students and staff",
    },
    {
        name: "Medical Center",
        image: images.medical,
        icon: "plus",
        note: "First aid and campus health support",
    },
    {
        name: "Hostel",
        image: images.hostel,
        icon: "building",
        note: "Supervised residential accommodation",
    },
];

const stats = ref([]);
let observer;

watch(
    statistics,
    (items) => {
        stats.value = items.map((item) => ({ ...item, display: 0 }));
    },
    { immediate: true },
);

const formatNumber = (value) => value.toLocaleString("en-PK");

onMounted(() => {
    const el = document.getElementById("ps-stats");
    if (!el) {
        return;
    }

    observer = new IntersectionObserver(
        (entries) => {
            if (!entries[0]?.isIntersecting) {
                return;
            }
            stats.value.forEach((stat, index) => {
                const start = performance.now();
                const tick = (now) => {
                    const progress = Math.min((now - start) / 900, 1);
                    stats.value[index].display = Math.round(
                        stat.value * progress,
                    );
                    if (progress < 1) {
                        requestAnimationFrame(tick);
                    }
                };
                requestAnimationFrame(tick);
            });
            observer.disconnect();
        },
        { threshold: 0.35 },
    );

    observer.observe(el);
});

onUnmounted(() => observer?.disconnect());
</script>

<template>
    <PublicSiteLayout>
        <Head title="Home">
            <meta
                head-key="description"
                name="description"
                content="Bright Future Educational Institute — academic excellence, character development, and student success in Lahore, Pakistan."
            />
            <link
                head-key="canonical"
                rel="canonical"
                :href="route('publicSite.home')"
            />
        </Head>

        <section class="ps-hero" style="background-image: url('/images/hero-home.avif'), url('/images/hero-about.jpg')">
            <picture>
                <source srcset="/images/hero-home.avif" type="image/avif" />
                <img
                    class="ps-hero-media"
                    src="/images/hero-about.jpg"
                    alt="Bright Future Educational Institute campus"
                    style="object-position: center 42%"
                />
            </picture>
            <div class="ps-hero-overlay" />
            <div class="ps-container">
                <div class="ps-hero-content" data-aos="fade-up">
                    <p class="ps-kicker">
                        Admissions Open · Academic Session 2026–27
                    </p>
                    <h1 class="ps-h1">Empowering Minds. Building Futures.</h1>
                    <p class="ps-lead">
                        Welcome to Bright Future Educational Institute, where
                        academic excellence, character development, innovation,
                        and leadership come together to prepare students for a
                        successful future.
                    </p>
                    <div class="ps-hero-facts">
                        <span class="ps-hero-fact">
                            <PublicIcon name="calendar" />
                            Established 2010
                        </span>
                        <span class="ps-hero-fact">
                            <PublicIcon name="pin" />
                            Lahore, Pakistan
                        </span>
                        <span class="ps-hero-fact">
                            <PublicIcon name="book" />
                            30+ Academic Programmes
                        </span>
                        <span class="ps-hero-fact">
                            <PublicIcon name="clipboard" />
                            Admissions Open 2026–27
                        </span>
                    </div>
                    <div class="ps-actions">
                        <Link
                            :href="route('publicSite.admissions')"
                            class="ps-btn ps-btn-primary"
                        >
                            Apply Now
                        </Link>
                        <Link
                            :href="route('publicSite.academics')"
                            class="ps-btn ps-btn-outline"
                        >
                            Explore Programs
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <section class="ps-section ps-section-muted py-20">
            <div class="ps-container grid lg:grid-cols-12 gap-12 items-center">
                <!-- Left Content Side -->
                <div class="lg:col-span-6" data-aos="fade-right">
                    <!-- Professional Kicker with letter spacing -->
                    <span
                        class="text-primary font-bold uppercase tracking-widest text-sm mb-4 block"
                    >
                        About Our Institution
                    </span>

                    <!-- Heading with a subtle accent line -->
                    <h2
                        class="text-4xl lg:text-5xl font-extrabold text-slate-900 leading-tight mb-6 relative"
                    >
                        Excellence in Education Since 2010
                        <span
                            class="block h-1.5 w-20 bg-primary mt-4 rounded-full"
                        ></span>
                    </h2>

                    <!-- Description with better line height and color contrast -->
                    <p
                        class="text-lg text-slate-600 leading-relaxed mb-8 max-w-lg"
                    >
                        Bright Future Educational Institute is committed to
                        providing high-quality education in a supportive,
                        innovative, and inclusive environment. Our institution
                        focuses on academic excellence, personal development,
                        leadership, and preparation for the future.
                    </p>

                    <div class="ps-actions">
                        <Link
                            :href="route('publicSite.about')"
                            class="ps-btn ps-btn-primary shadow-lg hover:shadow-xl transition-all px-8 py-3 rounded-md"
                        >
                            Discover Our Institution
                        </Link>
                    </div>
                </div>

                <!-- Right Stats Side -->
                <div
                    id="ps-stats"
                    class="lg:col-span-6 grid grid-cols-1 sm:grid-cols-2 gap-6"
                    data-aos="fade-left"
                >
                    <article
                        v-for="stat in stats"
                        :key="stat.label"
                        class="bg-white p-8 rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow duration-300"
                    >
                        <p class="text-3xl font-bold text-primary mb-2">
                            {{ formatNumber(stat.display) }}{{ stat.suffix }}
                        </p>
                        <p
                            class="text-slate-500 font-medium uppercase tracking-wide text-xs"
                        >
                            {{ stat.label }}
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <section class="ps-section ps-programs">
            <div class="ps-container">
                <div class="ps-programs-head" data-aos="fade-up">
                    <p class="ps-programs-kicker">Academic Pathways</p>
                    <h2 class="ps-programs-title">
                        Explore Our Academic Programs
                    </h2>
                    <span class="ps-programs-rule" aria-hidden="true" />
                    <p class="ps-programs-lead">
                        Rigorous, well-structured pathways for
                        {{ typeLabel.toLowerCase() }} students — each programme
                        defined by level, duration, and a clear route to the
                        next stage of study or professional practice.
                    </p>
                </div>
                <div class="ps-programs-grid">
                    <Link
                        v-for="(program, index) in programs"
                        :key="program.id"
                        :href="`${route('publicSite.academics')}?program=${program.id}`"
                        class="ps-program-card"
                        data-aos="fade-up"
                        :data-aos-delay="(index % 3) * 90"
                    >
                        <span class="ps-program-card-accent" aria-hidden="true" />
                        <div class="ps-program-card-top">
                            <span class="ps-program-icon">
                                <PublicIcon name="book" />
                            </span>
                            <span class="ps-program-level">{{ program.level }}</span>
                        </div>
                        <h3 class="ps-program-name">{{ program.name }}</h3>
                        <p class="ps-program-copy">{{ program.description }}</p>
                        <div class="ps-program-card-foot">
                            <span class="ps-program-duration">
                                <PublicIcon name="calendar" />
                                {{ program.duration }}
                            </span>
                            <span class="ps-program-cta">
                                View Program
                                <PublicIcon name="arrow" />
                            </span>
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <section class="ps-section ps-section-muted ps-why">
            <div class="ps-container">
                <div class="ps-why-head" data-aos="fade-up">
                    <p class="ps-why-kicker">Our Distinction</p>
                    <h2 class="ps-why-title">Why Choose Our Institution?</h2>
                    <span class="ps-why-rule" aria-hidden="true" />
                    <p class="ps-why-lead">
                        A complete academic environment — expert teaching,
                        modern facilities, and student support that prepares
                        learners for the next stage of study and professional
                        life.
                    </p>
                </div>
                <div class="ps-why-grid">
                    <article
                        v-for="(item, index) in whyItems"
                        :key="item.title"
                        class="ps-why-card"
                        data-aos="fade-up"
                        :data-aos-delay="(index % 3) * 90"
                    >
                        <div class="ps-why-card-top">
                            <span class="ps-why-icon" aria-hidden="true">
                                <PublicIcon :name="item.icon" />
                            </span>
                            <span class="ps-why-index">
                                {{ String(index + 1).padStart(2, "0") }}
                            </span>
                        </div>
                        <h3 class="ps-why-name">{{ item.title }}</h3>
                        <p class="ps-why-copy">{{ item.body }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="ps-section ps-leader">
            <div class="ps-container">
                <div class="ps-leader-head" data-aos="fade-up">
                    <p class="ps-leader-kicker">Leadership</p>
                    <h2 class="ps-leader-title">
                        Message from the {{ leader.designation }}
                    </h2>
                    <span class="ps-leader-rule" aria-hidden="true" />
                    <p class="ps-leader-lead">
                        A word from {{ leader.name }} on academic excellence,
                        character, and the purpose of education at our
                        institution.
                    </p>
                </div>

                <article class="ps-leader-card" data-aos="fade-up">
                    <div class="ps-leader-media">
                        <img
                            :src="leader.photo"
                            :alt="leader.name"
                        />
                        <span class="ps-leader-media-badge">
                            <PublicIcon name="cap" />
                            {{ leader.designation }}
                        </span>
                    </div>
                    <div class="ps-leader-body">
                        <span class="ps-leader-quote-icon" aria-hidden="true">
                            <PublicIcon name="quote" />
                        </span>
                        <blockquote class="ps-leader-quote">
                            Education is not simply about acquiring knowledge.
                            It is about developing the skills, character,
                            confidence, and vision required to make a meaningful
                            contribution to society.
                        </blockquote>
                        <div class="ps-leader-person">
                            <span class="ps-leader-person-icon" aria-hidden="true">
                                <PublicIcon name="user" />
                            </span>
                            <div>
                                <p class="ps-leader-name">{{ leader.name }}</p>
                                <p class="ps-leader-role">{{ leader.designation }}</p>
                            </div>
                        </div>
                        <Link
                            :href="`${route('publicSite.about')}#leadership`"
                            class="ps-btn ps-btn-primary"
                        >
                            Read More About Leadership
                            <PublicIcon name="arrow" />
                        </Link>
                    </div>
                </article>
            </div>
        </section>

        <section class="ps-section ps-section-muted ps-updates">
            <div class="ps-container">
                <div class="ps-updates-head" data-aos="fade-up">
                    <p class="ps-updates-kicker">Campus Updates</p>
                    <h2 class="ps-updates-title">News & Upcoming Events</h2>
                    <span class="ps-updates-rule" aria-hidden="true" />
                    <p class="ps-updates-lead">
                        Official notices, campus stories, and key dates for
                        students, parents, and applicants.
                    </p>
                </div>
                <div class="ps-updates-grid">
                    <div class="ps-updates-col">
                        <h3 class="ps-updates-col-title">
                            <PublicIcon name="bell" />
                            Latest News
                        </h3>
                        <div class="ps-updates-list">
                        <Link
                            v-for="(item, index) in news"
                            :key="item.title"
                            :href="route('publicSite.news')"
                            class="ps-news-card"
                            data-aos="fade-up"
                            :data-aos-delay="index * 80"
                        >
                            <span class="ps-news-thumb">
                                <img :src="item.image" :alt="item.title" />
                            </span>
                            <span class="ps-news-body">
                                <span class="ps-news-meta">
                                    <span class="ps-news-cat">
                                        <PublicIcon :name="item.icon" />
                                        {{ item.category }}
                                    </span>
                                    <span class="ps-news-date">
                                        <PublicIcon name="calendar" />
                                        {{ item.date }}
                                    </span>
                                </span>
                                <span class="ps-news-name">{{ item.title }}</span>
                                <span class="ps-news-copy">{{ item.excerpt }}</span>
                            </span>
                        </Link>
                        </div>
                    </div>
                    <div class="ps-updates-col">
                        <h3 class="ps-updates-col-title">
                            <PublicIcon name="calendar" />
                            Upcoming Events
                        </h3>
                        <div class="ps-updates-list">
                        <article
                            v-for="(event, index) in events"
                            :key="event.title"
                            class="ps-event-card"
                            data-aos="fade-up"
                            :data-aos-delay="index * 80"
                        >
                            <div class="ps-event-date" aria-hidden="true">
                                <span class="ps-event-day">{{ event.day }}</span>
                                <span class="ps-event-month">{{ event.month }}</span>
                            </div>
                            <div class="ps-event-body">
                                <h3 class="ps-event-name">{{ event.title }}</h3>
                                <p class="ps-event-meta">
                                    <span>
                                        <PublicIcon name="calendar" />
                                        {{ event.date }}
                                    </span>
                                    <span>
                                        <PublicIcon name="pin" />
                                        {{ event.place }}
                                    </span>
                                </p>
                            </div>
                            <span class="ps-event-icon" aria-hidden="true">
                                <PublicIcon :name="event.icon" />
                            </span>
                        </article>
                        </div>
                    </div>
                </div>
                <div class="ps-updates-cta">
                    <Link
                        :href="route('publicSite.news')"
                        class="ps-btn ps-btn-primary"
                    >
                        View All News & Events
                        <PublicIcon name="arrow" />
                    </Link>
                </div>
            </div>
        </section>

        <section class="ps-section ps-facilities">
            <div class="ps-container">
                <div class="ps-facilities-head" data-aos="fade-up">
                    <p class="ps-facilities-kicker">Campus Environment</p>
                    <h2 class="ps-facilities-title">Campus Facilities</h2>
                    <span class="ps-facilities-rule" aria-hidden="true" />
                    <p class="ps-facilities-lead">
                        Purpose-built spaces that support teaching, research,
                        sport, and student life across the campus.
                    </p>
                </div>
                <div class="ps-facilities-grid">
                    <Link
                        v-for="(item, index) in facilities"
                        :key="item.name"
                        :href="route('publicSite.campus')"
                        class="ps-facility-card"
                        data-aos="fade-up"
                        :data-aos-delay="(index % 3) * 90"
                    >
                        <img :src="item.image" :alt="item.name" />
                        <span class="ps-facility-shade" aria-hidden="true" />
                        <span class="ps-facility-icon">
                            <PublicIcon :name="item.icon" />
                        </span>
                        <span class="ps-facility-copy">
                            <span class="ps-facility-name">{{ item.name }}</span>
                            <span class="ps-facility-note">{{ item.note }}</span>
                        </span>
                    </Link>
                </div>
            </div>
        </section>

        <section class="ps-section ps-section-navy">
            <div class="ps-container" style="text-align: center">
                <h2 class="ps-h2">Start Your Journey With Us</h2>
                <p
                    class="ps-lead"
                    style="margin-left: auto; margin-right: auto"
                >
                    Applications for the 2026–27 academic session are now open.
                </p>
                <div class="ps-actions" style="justify-content: center">
                    <Link
                        :href="route('publicSite.admissions')"
                        class="ps-btn ps-btn-secondary"
                        >Apply Now</Link
                    >
                    <Link
                        :href="`${route('publicSite.news')}#downloads`"
                        class="ps-btn ps-btn-outline"
                    >
                        Download Prospectus
                    </Link>
                </div>
            </div>
        </section>
    </PublicSiteLayout>
</template>
