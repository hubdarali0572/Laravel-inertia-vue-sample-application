<script setup>
import { Head } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import PublicSiteLayout from "@/Layouts/PublicSite/PublicSiteLayout.vue";
import PublicPageHero from "@/Components/PublicSite/PublicPageHero.vue";
import PublicModal from "@/Components/PublicSite/PublicModal.vue";
import PublicIcon from "@/Components/PublicSite/PublicIcon.vue";

const article = ref(null);
const downloadNotice = ref(false);
const downloadFilter = ref("All");

const images = {
    sports: "/images/campus/sports.jpg",
    science: "/images/campus/science.jpg",
    admissions: "/images/hero-admissions.jpg",
    campus: "/images/campus-life.jpg",
    students: "/images/campus/students.jpg",
    events: "/images/campus/events.jpg",
    classroom: "/images/campus/classroom.jpg",
    library: "/images/campus/library.jpg",
    computer: "/images/campus/computer.jpg",
    auditorium: "/images/campus/auditorium.jpg",
};

const articles = [
    {
        title: "Annual Sports Gala 2026",
        date: "12 March 2026",
        category: "Campus",
        icon: "trophy",
        excerpt: "Students participated in football, cricket, athletics, badminton, and other sporting activities.",
        image: images.sports,
        body: "The Annual Sports Gala brought together houses and departments for a full day of competition. Faculty members and families attended the closing ceremony, where medals were awarded for team and individual events.",
    },
    {
        title: "Science Exhibition 2026",
        date: "18 February 2026",
        category: "Academics",
        icon: "flask",
        excerpt: "Learners presented laboratory projects, models, and investigative posters to visitors and judges.",
        image: images.science,
        body: "The science exhibition highlighted student inquiry across biology, chemistry, physics, and computing. Outstanding projects received certificates and mentoring for regional competitions.",
    },
    {
        title: "New Academic Session Announced",
        date: "01 August 2026",
        category: "Admissions",
        icon: "clipboard",
        excerpt: "The Institute has announced admissions and key dates for academic session 2026–27.",
        image: images.admissions,
        body: "Admissions are open for the forthcoming session. Prospective students should review eligibility, fee structure, and document requirements on the Admissions page.",
    },
    {
        title: "Career Development Seminar",
        date: "04 April 2026",
        category: "Students",
        icon: "briefcase",
        excerpt: "Industry guests spoke with students about higher education, internships, and professional skills.",
        image: images.auditorium,
        body: "The seminar covered CV writing, interview practice, and subject choices for competitive programmes. Students were invited to book follow-up counselling sessions.",
    },
    {
        title: "Orientation Week Highlights",
        date: "08 September 2025",
        category: "Campus",
        icon: "users",
        excerpt: "New students were introduced to faculty, facilities, academic regulations, and student societies.",
        image: images.students,
        body: "Orientation week included campus tours, library induction, and an introduction to the learning management system. Parents were invited to an information session on the final day.",
    },
    {
        title: "Community Service Drive",
        date: "20 January 2026",
        category: "Students",
        icon: "heart",
        excerpt: "Student societies organized a book donation and neighbourhood cleanliness activity.",
        image: images.events,
        body: "The community service drive is part of our commitment to responsible citizenship. Volunteers logged hours through Student Affairs and reflected on the experience in tutorial groups.",
    },
    {
        title: "Computer Labs Upgraded",
        date: "16 May 2026",
        category: "Academics",
        icon: "monitor",
        excerpt: "New workstations and licensed software now support programming, design, and research classes.",
        image: images.computer,
        body: "The computing laboratories have been upgraded with networked workstations, updated development tools, and improved projection for practical teaching. Students can book additional lab hours through the academic office.",
    },
    {
        title: "Parent Open Day",
        date: "22 November 2025",
        category: "Campus",
        icon: "building",
        excerpt: "Families toured classrooms, laboratories, and student support offices ahead of the new academic session.",
        image: images.classroom,
        body: "Parent Open Day offered campus tours, meetings with faculty, and briefings on admissions, transport, and the learning portal. Visitors received a printed information pack from the admissions desk.",
    },
];

const events = [
    { date: "05 September 2026", day: "05", month: "Sep", title: "Orientation Day", place: "Main Auditorium", time: "09:00 AM", icon: "users" },
    { date: "15 September 2026", day: "15", month: "Sep", title: "Annual Sports Gala", place: "Sports Complex", time: "08:30 AM", icon: "trophy" },
    { date: "20 September 2026", day: "20", month: "Sep", title: "Parent-Teacher Meeting", place: "Academic Block", time: "10:00 AM", icon: "clipboard" },
    { date: "25 September 2026", day: "25", month: "Sep", title: "Career Development Seminar", place: "Seminar Hall", time: "11:00 AM", icon: "briefcase" },
    { date: "10 October 2026", day: "10", month: "Oct", title: "Science Exhibition", place: "Science Block", time: "09:30 AM", icon: "flask" },
    { date: "14 August 2026", day: "14", month: "Aug", title: "Independence Day Assembly", place: "Campus Lawn", time: "08:00 AM", icon: "star" },
];

const notices = [
    { title: "Undergraduate Merit List", icon: "clipboard", date: "05 October 2026", body: "The first merit list for undergraduate programmes will be published on the notices board and student portal." },
    { title: "Fee Submission Deadline", icon: "calendar", date: "12 October 2026", body: "Newly admitted students must submit dues by the published date to confirm enrolment." },
    { title: "Midterm Examination Schedule", icon: "book", date: "09 November 2026", body: "Midterm dates, venues, and seating plans will be issued by the examination office." },
    { title: "Library Hours Update", icon: "building", date: "01 September 2026", body: "The central library will remain open from 8:00 AM to 7:00 PM during the teaching term." },
    { title: "Campus Transport Routes", icon: "bus", date: "18 August 2026", body: "Updated pickup points and timings for Lahore routes are available from the transport office." },
    { title: "Holiday Calendar", icon: "bell", date: "24 December 2026", body: "Winter break will run from 24 December 2026 to 02 January 2027. Classes resume on 03 January." },
];

const downloads = [
    { title: "Prospectus 2026–27", category: "Admissions", icon: "book", body: "Programme outlines, eligibility, and fee information for the new session." },
    { title: "Application Form", category: "Admissions", icon: "clipboard", body: "Official application form for undergraduate and graduate entry." },
    { title: "Fee Challan", category: "Admissions", icon: "briefcase", body: "Bank challan for application and admission fee payment." },
    { title: "Academic Calendar", category: "Academics", icon: "calendar", body: "Term dates, examinations, and holidays for session 2026–27." },
    { title: "Code of Conduct", category: "Students", icon: "check", body: "Campus regulations, attendance expectations, and student responsibilities." },
    { title: "Transport Routes", category: "Campus", icon: "bus", body: "Published pickup points and timings for campus transport services." },
];

const highlights = [
    { title: "Debate Team Regional Trophy", icon: "trophy", body: "The debating society placed first in the regional inter-college championship." },
    { title: "Computing Internships", icon: "monitor", body: "Final-year computing students completed summer internships with industry partners." },
    { title: "Research Poster Awards", icon: "flask", body: "Science students received recognition for investigative posters at the annual exhibition." },
    { title: "Volunteer Hours Record", icon: "heart", body: "Student societies logged a record number of community service hours this term." },
    { title: "Sports Medals Sweep", icon: "star", body: "Athletics and football teams returned with medals from the inter-house sports gala." },
    { title: "Cultural Night Success", icon: "swatch", body: "Music, drama, and language performances drew families and alumni to the auditorium." },
];

const categories = computed(() => ["All", ...new Set(downloads.map((item) => item.category))]);

const visibleDownloads = computed(() =>
    downloadFilter.value === "All"
        ? downloads
        : downloads.filter((item) => item.category === downloadFilter.value),
);
</script>

<template>
    <PublicSiteLayout>
        <Head title="News & Events">
            <meta
                head-key="description"
                name="description"
                content="Latest news, upcoming events, official notices, and downloadable documents from Bright Future Educational Institute."
            />
            <link head-key="canonical" rel="canonical" :href="route('publicSite.news')" />
        </Head>

        <PublicPageHero
            image="/images/hero-news.jpg"
            object-position="center 40%"
            alt="Campus assembly and events at Bright Future Educational Institute"
            kicker="Campus Updates"
            title="News, Events & Official Notices"
            description="Stay informed with campus news, upcoming events, official notices, and downloadable documents for students, parents, and applicants."
            :crumbs="[{ label: 'News & Events' }]"
            :facts="[
                { icon: 'bell', label: 'Official Notices' },
                { icon: 'calendar', label: 'Upcoming Events' },
                { icon: 'download', label: 'Prospectus & Forms' },
                { icon: 'users', label: 'Student Life Stories' },
            ]"
        />

        <section class="ps-section">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">News</h2>
                    <p class="ps-lead">Campus stories, academic updates, and student life from the current session.</p>
                </div>
                <div class="ps-story-grid">
                    <article
                        v-for="(item, index) in articles"
                        :key="item.title"
                        class="ps-story-card"
                        :class="{ 'is-featured': index === 0 }"
                        data-aos="fade-up"
                        :data-aos-delay="(index % 3) * 80"
                    >
                        <div class="ps-story-media">
                            <img :src="item.image" :alt="item.title" />
                            <span class="ps-story-badge">
                                <PublicIcon :name="item.icon" />
                                {{ item.category }}
                            </span>
                        </div>
                        <div class="ps-story-body">
                            <p class="ps-story-date">
                                <PublicIcon name="calendar" />
                                {{ item.date }}
                            </p>
                            <h3 class="ps-story-name">{{ item.title }}</h3>
                            <p class="ps-story-copy">{{ item.excerpt }}</p>
                            <button type="button" class="ps-btn ps-btn-ghost ps-story-more" @click="article = item">
                                Read More
                                <PublicIcon name="arrow" />
                            </button>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="ps-section ps-section-muted">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">Upcoming Events</h2>
                    <p class="ps-lead">Key campus dates for orientation, sport, exhibitions, and family meetings.</p>
                </div>
                <div class="ps-grid ps-grid-2">
                    <article
                        v-for="(item, index) in events"
                        :key="item.title"
                        class="ps-event-card"
                        data-aos="fade-up"
                        :data-aos-delay="(index % 2) * 80"
                    >
                        <div class="ps-event-date" aria-hidden="true">
                            <span class="ps-event-day">{{ item.day }}</span>
                            <span class="ps-event-month">{{ item.month }}</span>
                        </div>
                        <div class="ps-event-body">
                            <h3 class="ps-event-name">{{ item.title }}</h3>
                            <p class="ps-event-meta">
                                <span>
                                    <PublicIcon name="calendar" />
                                    {{ item.date }} · {{ item.time }}
                                </span>
                                <span>
                                    <PublicIcon name="pin" />
                                    {{ item.place }}
                                </span>
                            </p>
                        </div>
                        <span class="ps-event-icon" aria-hidden="true">
                            <PublicIcon :name="item.icon" />
                        </span>
                    </article>
                </div>
            </div>
        </section>

        <section class="ps-section">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">Official Notices</h2>
                    <p class="ps-lead">Circulars from admissions, examinations, the library, and campus administration.</p>
                </div>
                <div class="ps-grid ps-grid-3">
                    <article
                        v-for="(item, index) in notices"
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
                        <p class="ps-unit-deadline">
                            <PublicIcon name="calendar" />
                            <span>{{ item.date }}</span>
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <section class="ps-section">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">Student Highlights</h2>
                    <p class="ps-lead">Recent achievements in sport, research, community service, and campus culture.</p>
                </div>
                <div class="ps-grid ps-grid-3">
                    <article
                        v-for="(item, index) in highlights"
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

        <PublicModal :open="Boolean(article)" :title="article?.title || ''" @close="article = null">
            <p v-if="article" class="ps-caption">{{ article.category }} · {{ article.date }}</p>
            <p v-if="article" class="ps-mt-4">{{ article.body }}</p>
        </PublicModal>

        <PublicModal :open="downloadNotice" title="Document Request" @close="downloadNotice = false">
            <p>
                Official files are issued by the relevant office. Please contact Admissions or the Academic Office through the Contact page to request the current prospectus, form, or calendar.
            </p>
        </PublicModal>
    </PublicSiteLayout>
</template>
