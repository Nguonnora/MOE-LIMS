<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_TECHNICIAN = 'technician';
    const ROLE_APPROVER = 'approver';
    const ROLE_RECEPTIONIST = 'receptionist';
    const ROLE_VIEWER = 'viewer';

    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin() { return $this->role === self::ROLE_ADMIN; }
    public function isTechnician() { return $this->role === self::ROLE_TECHNICIAN; }
    public function isApprover() { return $this->role === self::ROLE_APPROVER; }
    public function isReceptionist() { return $this->role === self::ROLE_RECEPTIONIST; }
    public function isViewer() { return $this->role === self::ROLE_VIEWER; }
}