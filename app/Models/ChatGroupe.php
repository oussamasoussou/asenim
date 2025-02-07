<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatGroupe extends Model
{
    use HasFactory;

    protected $table = 'chat_groupe';
    protected $fillable = [
        'content',
        'user_id',
        'file_path',
        'file_name',
        'file_type',
    ];

    /**
     * Relation avec le modèle User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
