import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

function lookup(source, key) {
    return key.split(".").reduce((value, part) => {
        if (value && typeof value === "object" && part in value) {
            return value[part];
        }

        return undefined;
    }, source);
}

export function useI18n() {
    const page = usePage();

    const locale = computed(() => page.props.locale || "en");
    const dir = computed(() => page.props.dir || "ltr");
    const translations = computed(() => page.props.translations || {});

    const t = (key, replace = {}) => {
        const resolved = lookup(translations.value, key);
        let text = typeof resolved === "string" ? resolved : key;

        if (text.includes("|") && Object.prototype.hasOwnProperty.call(replace, "count")) {
            const parts = text.split("|");
            const count = Number(replace.count);
            text = Number.isNaN(count) || count === 1 ? parts[0] : parts[parts.length - 1];
        }

        Object.entries(replace).forEach(([name, value]) => {
            text = text.replaceAll(`:${name}`, String(value ?? ""));
        });

        return text;
    };

    return {
        t,
        locale,
        dir,
        isRtl: computed(() => dir.value === "rtl"),
    };
}
