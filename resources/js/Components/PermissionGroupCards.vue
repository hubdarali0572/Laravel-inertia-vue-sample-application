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

const selectedCount = (ids, permissions) =>
    permissions.filter((permission) => isChecked(ids, permission.id)).length;

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
    <div class="theme-form-card">
        <div class="theme-form-section-header">
            <h3 class="theme-form-section-title">
                {{ t("roles.permissions_title") }}
            </h3>
        </div>

        <div class="theme-perm-grid">
            <div
                v-for="(permissions, groupName) in groups"
                :key="groupName"
                class="theme-perm-card"
            >
                <div class="theme-perm-card-header">
                    <div class="min-w-0">
                        <h3 class="theme-perm-card-title">
                            {{ t(groupName) }}
                        </h3>
                        <p class="theme-perm-count mt-0.5">
                            {{ selectedCount(modelValue, permissions) }} /
                            {{ permissions.length }}
                        </p>
                    </div>
                    <label class="theme-perm-all">
                        <input
                            type="checkbox"
                            class="theme-form-checkbox h-3.5 w-3.5"
                            :checked="isGroupFull(modelValue, permissions)"
                            @change="
                                toggleGroup(
                                    modelValue,
                                    permissions,
                                    $event.target.checked,
                                )
                            "
                        />
                        {{ t("common.all") }}
                    </label>
                </div>

                <div class="theme-perm-body">
                    <label
                        v-for="permission in permissions"
                        :key="permission.id"
                        class="theme-perm-row"
                        :class="{
                            'is-checked': isChecked(modelValue, permission.id),
                        }"
                    >
                        <input
                            type="checkbox"
                            class="theme-form-checkbox h-4 w-4"
                            :checked="isChecked(modelValue, permission.id)"
                            @change="
                                togglePermission(
                                    modelValue,
                                    permission.id,
                                    $event.target.checked,
                                )
                            "
                        />
                        <span>{{ permissionLabel(permission.name) }}</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>
