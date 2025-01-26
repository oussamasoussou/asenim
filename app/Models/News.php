<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
