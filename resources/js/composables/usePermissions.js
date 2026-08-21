import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

export function usePermissions() {
    const page = usePage();

    const permissions = computed(() => page.props.auth?.permissions ?? []);
    const roles = computed(() => page.props.auth?.roles ?? []);
    const isSuperAdmin = computed(() => !!page.props.auth?.is_superadmin);
    const roleName = computed(
        () => page.props.auth?.user?.role_name || roles.value[0] || "User",
    );

    const can = (permission) => {
        if (isSuperAdmin.value) {
            return true;
        }

        if (Array.isArray(permission)) {
            return permission.some((name) => permissions.value.includes(name));
        }

        return permissions.value.includes(permission);
    };

    const canAny = (names) => names.some((name) => can(name));

    return {
        can,
        canAny,
        isSuperAdmin,
        permissions,
        roles,
        roleName,
    };
}
