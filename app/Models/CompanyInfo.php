<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Coordonnées de l'entreprise : une seule fiche pour toute l'application.
 */
class CompanyInfo extends Model
{
    protected $table = 'company_infos';

    protected $fillable = [
        'address',
        'phone',
        'email',
        'updated_by',
    ];

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }

    /**
     * Retourne la fiche existante, ou une fiche vide non enregistrée.
     */
    public static function current(): self
    {
        return static::query()->first() ?? new static();
    }

    public function isEmpty(): bool
    {
        return !$this->address && !$this->phone && !$this->email;
    }
}
