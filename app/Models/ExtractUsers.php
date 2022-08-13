<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtractUser extends Model
{
    use HasFactory;

    protected $table = 'extract_users';

    protected $fillable = [
        'reference',
        'value',
        'type',
    ];

    protected $hidden = [
        'transfer_id'
    ];
}
