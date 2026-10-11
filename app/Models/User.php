<?php

namespace App\Models;

use App\Notifications\CustomResetPasswordNotification;
use App\Support\RichText;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const ROLES = [
        'ADMIN' => 'admin',
        'MEMBRE' => 'membre',
    ];
   
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
        'first_name',
        'last_name',
        'phone',
        'image',
        'type',
        'first_connection',
        'user_connected',
        'institution',
        'grade',
        'orcid',
        'function',
        'biography',
        'activities',
        'level',
        'approved_at',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'approved_at' => 'datetime',
    ];


    /**
     * Biographie et activités : texte riche (barre d'outils) nettoyé à l'enregistrement.
     */
    public function setBiographyAttribute($value): void
    {
        $this->attributes['biography'] = RichText::sanitize($value);
    }

    public function setActivitiesAttribute($value): void
    {
        $this->attributes['activities'] = RichText::sanitize($value);
    }

    public function isAdmin()
    {
        return $this->role === self::ROLES['ADMIN'];
    }

    public function isMembre()
    {
        return $this->role === self::ROLES['MEMBRE'];
    }

    protected static function booted(): void
    {
        // Un compte est validé par défaut (création par un admin, seeder, import…).
        // Seules les inscriptions du site public passent explicitement approved_at = null.
        static::creating(function (User $user) {
            if (!array_key_exists('approved_at', $user->getAttributes())) {
                $user->approved_at = now();
            }
        });
    }

    /**
     * Compte validé par un administrateur (les inscriptions du site public sont en attente).
     */
    public function isApproved(): bool
    {
        return $this->approved_at !== null;
    }

    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_at');
    }

    public function scopePending($query)
    {
        return $query->whereNull('approved_at');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}
