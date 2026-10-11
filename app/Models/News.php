<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\RichText;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class News extends Model
{
    use HasFactory, softDeletes;
    protected $table = 'news';

    protected $fillable = [
        'title',
        'image',
        'content',
        'date',
        'events_news',
        'user_id'
    ];

    /**
     * Texte riche (barre d'outils) : nettoyé à l'enregistrement.
     */
    public function setContentAttribute($value): void
    {
        $this->attributes['content'] = RichText::sanitize($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
