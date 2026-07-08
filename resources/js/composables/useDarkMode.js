import { ref } from 'vue';

const isDark = ref(
    typeof document !== 'undefined' &&
        document.documentElement.classList.contains('dark')
);

const applyTheme = (dark) => {
    isDark.value = dark;

    if (typeof document !== 'undefined') {
        document.documentElement.classList.toggle('dark', dark);
    }

    try {
        localStorage.setItem('theme', dark ? 'dark' : 'light');
    } catch (e) {
        // Ignore storage errors (e.g. private mode)
    }
};

export function useDarkMode() {
    const toggleDarkMode = () => applyTheme(!isDark.value);

    return { isDark, toggleDarkMode };
}
