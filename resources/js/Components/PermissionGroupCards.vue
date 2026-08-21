<script setup>
import { useI18n } from "@/composables/useI18n";

defineProps({
    groups: {
        type: Object,
        required: true,
    },
    modelValue: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(["update:modelValue"]);
const { t } = useI18n();

const permissionLabel = (name) => {
    const action = (name || "").split(" ")[0];
    const translated = t(`permissions.${action}`);

    return translated.startsWith("permissions.") ? action : translated;
};

const selectedIds = (value) => value.map((id) => Number(id));

const isChecked = (ids, permissionId) =>
    selectedIds(ids).includes(Number(permissionId));

const isGroupFull = (ids, permissions) =>
    permissions.length > 0 &&
    permissions.every((permission) => isChecked(ids, permission.id));

const togglePermission = (ids, permissionId, checked) => {
    const current = selectedIds(ids);
    const id = Number(permissionId);

    if (checked) {
        if (!current.includes(id)) {
            emit("update:modelValue", [...current, id]);
        }
        return;
    }

    emit(
        "update:modelValue",
        current.filter((value) => value !== id),
    );
};

const toggleGroup = (ids, permissions, checked) => {
    const current = selectedIds(ids);
    const groupIds = permissions.map((permission) => Number(permission.id));

    if (checked) {
        emit("update:modelValue", [...new Set([...current, ...groupIds])]);
        return;
    }

    emit(
        "update:modelValue",
        current.filter((id) => !groupIds.includes(id)),
    );
};
</script>

<template>
    <div class="flex flex-wrap gap-3">
        <div
            v-for="(permissions, groupName) in groups"
            :key="groupName"
            class="w-40 shrink-0 overflow-hidden rounded-sm border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800"
        >
            <div
                class="flex items-center justify-between gap-1 border-b border-indigo-100 bg-indigo-50 px-2 py-1 dark:border-indigo-400/20 dark:bg-indigo-500/10"
            >
                <h3
                    class="min-w-0 truncate text-[9px] font-semibold uppercase tracking-wide text-indigo-700 dark:text-indigo-200"
                >
                    {{ t(groupName) }}
                </h3>
                <label class="inline-flex shrink-0 cursor-pointer items-center">
                    <input
                        type="checkbox"
                        class="theme-form-checkbox h-3 w-3"
                        :checked="isGroupFull(modelValue, permissions)"
                        @change="
                            toggleGroup(
                                modelValue,
                                permissions,
                                $event.target.checked,
                            )
                        "
                    />
                    <span
                        class="ml-1 text-[9px] font-bold uppercase text-indigo-700 dark:text-indigo-200"
                        >{{ t("common.all") }}</span
                    >
                </label>
            </div>

            <div class="flex flex-col px-1.5 py-1">
                <label
                    v-for="permission in permissions"
                    :key="permission.id"
                    class="flex cursor-pointer items-center gap-1.5 rounded px-1.5 py-1 hover:bg-indigo-50/70 dark:hover:bg-indigo-500/10"
                >
                    <input
                        type="checkbox"
                        class="theme-form-checkbox h-3.5 w-3.5"
                        :checked="isChecked(modelValue, permission.id)"
                        @change="
                            togglePermission(
                                modelValue,
                                permission.id,
                                $event.target.checked,
                            )
                        "
                    />
                    <span
                        class="text-xs font-semibold capitalize text-slate-700 dark:text-slate-200"
                    >
                        {{ permissionLabel(permission.name) }}
                    </span>
                </label>
            </div>
        </div>
    </div>
</template>
