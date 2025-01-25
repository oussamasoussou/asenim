<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Documents extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'document';

    protected $fillable = [
        'file_path',
        'member_type',
        'file_name',
    ];

    protected $casts = [
        'member_type' => 'string',
    ];
}
