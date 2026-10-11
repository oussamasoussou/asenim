<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Membre du Bureau exécutif (site public).
 */
class BoardMember extends Model
{
    protected $fillable = ['full_name', 'role', 'organization', 'email', 'quote', 'photo', 'position', 'is_visible'];

    protected $casts = [
        'is_visible' => 'boolean',
        'position' => 'integer',
    ];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position')->orderBy('id');
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    public function photoUrl(): string
    {
        return $this->photo ? asset('storage/' . $this->photo) : asset('front/assets/img/team/user.png');
    }

    /**
     * Citation affichée avec des guillemets français, qu'ils aient été saisis ou non.
     */
    public function displayQuote(): ?string
    {
        $quote = preg_replace('/^[\s«»"“”]+|[\s«»"“”]+$/u', '', (string) $this->quote);

        return $quote === '' ? null : '« ' . $quote . ' »';
    }
}
