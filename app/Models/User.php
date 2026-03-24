<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Role constants
     */
    public const ROLE_ADMIN = 'Admin';
    public const ROLE_MANAGER = 'Manager';
    public const ROLE_TEAM_LEAD = 'Team Lead';
    public const ROLE_AGENT = 'Agent';
    public const ROLE_BEADER = 'Beader';

    /**
     * Available roles
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_MANAGER => 'Manager',
            self::ROLE_TEAM_LEAD => 'Team Lead',
            self::ROLE_AGENT => 'Agent',
            self::ROLE_BEADER => 'Beader',
        ];
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    public function clients()
    {
        return $this->hasMany(Client::class, 'agent_id');
    }

    /**
     * Check if user is Admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is Manager
     */
    public function isManager(): bool
    {
        return $this->role === self::ROLE_MANAGER;
    }

    public function isAgent(): bool
    {
        return $this->role === self::ROLE_AGENT || $this->role === self::ROLE_BEADER;
    }

    /**
     * Check if user is Team Lead
     */
    public function isTeamLead(): bool
    {
        return $this->role === self::ROLE_TEAM_LEAD;
    }

    /**
     * Check if user is Beader
     */
    public function isBeader(): bool
    {
        return $this->role === self::ROLE_BEADER;
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Check if user has at least Admin or Manager role
     */
    public function isManagement(): bool
    {
        return $this->isAdmin() || $this->isManager() || $this->isTeamLead();
    }

    /**
     * Get role label
     */
    public function getRoleLabelAttribute(): string
    {
        return self::getRoles()[$this->role] ?? $this->role;
    }

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
}
