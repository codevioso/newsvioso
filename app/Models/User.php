<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Constants\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'reset_code',
        'reset_code_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'reset_code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'reset_code_expires_at' => 'datetime',
    ];

    /**
     * Check if user has specific role
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is super admin
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(UserRole::SUPER_ADMIN);
    }

    /**
     * Check if user is editor
     *
     * @return bool
     */
    public function isEditor(): bool
    {
        return $this->hasRole(UserRole::EDITOR);
    }

    /**
     * Check if user is reporter
     *
     * @return bool
     */
    public function isReporter(): bool
    {
        return $this->hasRole(UserRole::REPORTER);
    }

    /**
     * Check if user has permission level
     *
     * @param int $requiredLevel
     * @return bool
     */
    public function hasPermissionLevel(int $requiredLevel): bool
    {
        return UserRole::hasPermissionLevel($this->role, $requiredLevel);
    }

    /**
     * Get role display name
     *
     * @return string
     */
    public function getRoleDisplayName(): string
    {
        return UserRole::getDisplayName($this->role);
    }

    /**
     * Generate reset code
     *
     * @return string
     */
    public function generateResetCode(): string
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->update([
            'reset_code' => $code,
            'reset_code_expires_at' => now()->addMinutes(15),
        ]);
        return $code;
    }

    /**
     * Verify reset code
     *
     * @param string $code
     * @return bool
     */
    public function verifyResetCode(string $code): bool
    {
        return $this->reset_code === $code && 
               $this->reset_code_expires_at && 
               $this->reset_code_expires_at->isFuture();
    }

    /**
     * Clear reset code
     *
     * @return void
     */
    public function clearResetCode(): void
    {
        $this->update([
            'reset_code' => null,
            'reset_code_expires_at' => null,
        ]);
    }
}
