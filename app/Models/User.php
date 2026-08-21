<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Support\Permissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, HasRoles, InteractsWithMedia, LogsActivity, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'role_id',
        'user_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function isSuperAdmin(): bool
    {
        return $this->user_type === Permissions::SUPERADMIN_ROLE
            || $this->hasRole(Permissions::SUPERADMIN_ROLE);
    }

    public function syncAssignedRole(Role $role): void
    {
        $this->role_id = $role->id;
        $this->save();
        $this->syncRoles([$role]);
        $this->unsetRelation('roles');
        $this->unsetRelation('permissions');
        $this->unsetRelation('role');
        $this->forgetCachedPermissions();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->singleFile()
            ->useDisk('public');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('optimized')
            ->width(400)
            ->height(400)
            ->sharpen(5)
            ->performOnCollections('images')
            ->nonQueued()
            ->keepOriginalImageFormat();
    }

    public function getProfileImageUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('images');

        if (! $media) {
            return null;
        }

        if (is_file($media->getPath())) {
            return $media->getUrl();
        }

        if ($media->hasGeneratedConversion('optimized') && is_file($media->getPath('optimized'))) {
            return $media->getUrl('optimized');
        }

        return route('media.show', ['media' => $media->id]);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'image']) // Log these fields
            ->logOnlyDirty()                      // Only log if something actually changed
            ->dontSubmitEmptyLogs();              // Don't log if nothing changed
    }
}
