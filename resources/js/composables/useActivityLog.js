export function useActivityLog() {
    const formatDate = (date) => {
        if (!date) {
            return "—";
        }

        return new Date(date).toLocaleString("en-US", {
            month: "short",
            day: "numeric",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        });
    };

    const getModelName = (type) => {
        if (!type) {
            return "System";
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
            return value ? "Yes" : "No";
        }

        if (typeof value === "object") {
            return JSON.stringify(value);
        }

        return String(value);
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
            return "No detail recorded";
        }

        const event = log.description || log.event;
        if (event === "created") {
            return `${details.length} field${details.length === 1 ? "" : "s"} set`;
        }
        if (event === "deleted") {
            return `${details.length} field${details.length === 1 ? "" : "s"} removed`;
        }

        const changed = details.filter((item) => item.changed !== false);
        return `${changed.length} field${changed.length === 1 ? "" : "s"} changed`;
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
    };
}
