<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Models\Activity;

class ActivityLogger
{
    /**
     * Log a permission set change on a role (assign / remove).
     * Skips when the permission list is unchanged.
     *
     * @param  list<string>|Collection<int, string>  $oldPermissions
     * @param  list<string>|Collection<int, string>  $newPermissions
     */
    public static function logRolePermissionChanges(
        Model $role,
        iterable $oldPermissions,
        iterable $newPermissions,
    ): void {
        $old = collect($oldPermissions)->map(fn ($name) => (string) $name)->sort()->values()->all();
        $new = collect($newPermissions)->map(fn ($name) => (string) $name)->sort()->values()->all();

        if ($old === $new) {
            return;
        }

        activity()
            ->useLog('default')
            ->performedOn($role)
            ->causedBy(auth()->user())
            ->event('updated')
            ->withProperties([
                'old' => [
                    'permissions' => $old,
                ],
                'attributes' => [
                    'permissions' => $new,
                ],
                'added_permissions' => array_values(array_diff($new, $old)),
                'removed_permissions' => array_values(array_diff($old, $new)),
            ])
            ->log('updated');
    }

    /**
     * Attach permission names onto the latest "created" or "deleted" activity for a role.
     *
     * @param  list<string>|Collection<int, string>  $permissions
     */
    public static function attachPermissionsToRoleLog(
        Model $role,
        iterable $permissions,
        string $event = 'created',
    ): void {
        $names = collect($permissions)->map(fn ($name) => (string) $name)->sort()->values()->all();

        $activity = Activity::query()
            ->where('subject_type', $role->getMorphClass())
            ->where('subject_id', $role->getKey())
            ->where(function ($query) use ($event) {
                $query->where('description', $event)
                    ->orWhere('event', $event);
            })
            ->latest('id')
            ->first();

        if (! $activity) {
            return;
        }

        $properties = $activity->properties?->toArray() ?? [];
        $bucket = $event === 'deleted' ? 'old' : 'attributes';
        $properties[$bucket] = array_merge($properties[$bucket] ?? [], [
            'permissions' => $names,
        ]);
        $activity->properties = $properties;
        $activity->save();
    }
}
