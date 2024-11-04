<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'student_id',
        'phone_number',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        // For student panel, allow access during registration
        if ($panel->getId() === 'student') {
            return true; // Allow access to student panel by default
        }

        // For admin and manager panels, check roles strictly
        return match ($panel->getId()) {
            'admin' => $this->hasRole(['admin', 'super_admin']),
            'manager' => $this->hasRole('manager'),
            default => false
        };
    }
}
