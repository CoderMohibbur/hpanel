<?php

namespace App\Models;

use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams; // ✅ Jetstream teams support
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;
    use HasRoles; // ✅ Spatie roles/permissions support
    
    // ✅ H Panel SRS constants
    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_RESELLER    = 'reseller';
    public const ROLE_CLIENT      = 'client';

    public const APPROVAL_PENDING   = 'pending';
    public const APPROVAL_APPROVED  = 'approved';
    public const APPROVAL_REJECTED  = 'rejected';
    public const APPROVAL_SUSPENDED = 'suspended';

    public const PROFILE_INCOMPLETE = 'incomplete';
    public const PROFILE_SUBMITTED  = 'submitted';
    public const PROFILE_VERIFIED   = 'verified';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',

        // ✅ H Panel fields
        'role',
        'approval_status',
        'parent_reseller_id',
        'reseller_profile_status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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

    // ✅ Reseller tree relationships
    public function parentReseller()
    {
        return $this->belongsTo(self::class, 'parent_reseller_id');
    }

    public function childResellers()
    {
        return $this->hasMany(self::class, 'parent_reseller_id');
    }

    // ✅ Helpers
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isReseller(): bool
    {
        return $this->role === self::ROLE_RESELLER;
    }

    public function isApproved(): bool
    {
        return $this->approval_status === self::APPROVAL_APPROVED;
    }
}
