<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtractUser extends Model
{
    use HasFactory;

    protected $table = 'extract_users';

    protected $fillable = [
        'user_id',
        'reference',
        'value',
        'type',
        'protocol'
    ];

    protected $hidden = [
        'protocol'
    ];
}
