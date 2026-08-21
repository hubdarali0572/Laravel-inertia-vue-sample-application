import { usePage } from "@inertiajs/vue3";
import { useI18n } from "@/composables/useI18n";

export function useActivityLog() {
    const page = usePage();
    const { t } = useI18n();

    const dateLocale = () =>
        page.props.locale === "ur" ? "ur-PK" : "en-US";

    const formatDate = (date) => {
        if (!date) {
            return "—";
        }

        return new Date(date).toLocaleString(dateLocale(), {
            month: "short",
            day: "numeric",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        });
    };

    const getModelName = (type) => {
        if (!type) {
            return t("common.system");
        }

        return type.split("\\").pop();
    };

    const formatFieldLabel = (key) => {
        return key
            .replace(/_/g, " ")
            .replace(/\b\w/g, (char) => char.toUpperCase());
    };

    const normalizeProperties = (log) => {
        const raw = log?.properties;

        if (!raw) {
            return { old: {}, attributes: {} };
        }

        if (typeof raw === "string") {
            try {
                return JSON.parse(raw);
            } catch {
                return { old: {}, attributes: {} };
            }
        }

        return raw;
    };

    const formatValue = (value) => {
        if (value === null || value === undefined || value === "") {
            return "—";
        }

        if (typeof value === "boolean") {
            return value ? t("common.yes") : t("common.no");
        }

        if (typeof value === "object") {
            return JSON.stringify(value);
        }

        return String(value);
    };

    const eventLabel = (event) => {
        const map = {
            created: "activity.created",
            updated: "activity.updated",
            deleted: "activity.deleted",
        };

        return map[event] ? t(map[event]) : event;
    };

    const getChangeDetails = (log) => {
        const properties = normalizeProperties(log);
        const oldValues = properties.old || {};
        const newValues = properties.attributes || {};
        const event = log.description || log.event;
        const hiddenFields = new Set(["password", "remember_token"]);
        const isVisibleField = (field) => !hiddenFields.has(field);

        if (event === "created") {
            return Object.entries(newValues)
                .filter(([field]) => isVisibleField(field))
                .map(([field, value]) => ({
                    field,
                    label: formatFieldLabel(field),
                    type: "created",
                    new: value,
                }));
        }

        if (event === "deleted") {
            return Object.entries(oldValues)
                .filter(([field]) => isVisibleField(field))
                .map(([field, value]) => ({
                    field,
                    label: formatFieldLabel(field),
                    type: "deleted",
                    old: value,
                }));
        }

        const fields = new Set([
            ...Object.keys(oldValues),
            ...Object.keys(newValues),
        ]);

        return [...fields].filter(isVisibleField).map((field) => ({
            field,
            label: formatFieldLabel(field),
            type: "updated",
            old: oldValues[field],
            new: newValues[field],
            changed: oldValues[field] !== newValues[field],
        }));
    };

    const hasChangeDetails = (log) => getChangeDetails(log).length > 0;

    const changeSummary = (log) => {
        const details = getChangeDetails(log);
        if (!details.length) {
            return t("activity.no_detail");
        }

        const event = log.description || log.event;
        const count =
            event === "created" || event === "deleted"
                ? details.length
                : details.filter((item) => item.changed !== false).length;

        return t("activity.fields_changed", { count });
    };

    const actionBadgeClass = (event) => {
        if (event === "created") {
            return "bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-200";
        }

        if (event === "deleted") {
            return "bg-slate-200 text-slate-700 dark:bg-slate-600 dark:text-slate-200";
        }

        return "bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-200";
    };

    return {
        formatDate,
        getModelName,
        formatValue,
        getChangeDetails,
        hasChangeDetails,
        changeSummary,
        actionBadgeClass,
        normalizeProperties,
        eventLabel,
    };
}
