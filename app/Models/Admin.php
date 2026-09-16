<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'image',
        'bank_name',
        'account_number',
        'ifsc_code',
        'branch_name',
        'account_type',
        'upi_id',
        'qr_code_image',
        'email',
        'mobile',
        'password',
        'role',
        'role_id',
        'role_slug',
        'status',
        'remember_token',
        'last_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'password' => 'hashed',
            'last_login' => 'datetime',
        ];
    }

    /**
     * The roles & permissions role assigned to this admin.
     */
    public function assignedRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Whether this admin is the super admin (full access).
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin'
            || ($this->assignedRole && $this->assignedRole->slug === 'super-admin');
    }

    /**
     * Whether this admin can access the given permission slug.
     * The super admin always has access to everything.
     */
    public function hasAccess(string $slug): bool
    {
        return $this->isSuperAdmin()
            || ($this->assignedRole && $this->assignedRole->hasPermission($slug));
    }
}
