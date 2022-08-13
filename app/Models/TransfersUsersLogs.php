<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransfersUsersLogs extends Model
{
    use HasFactory;

    protected $table = 'transfers_users_logs';

    protected $fillable = [
        'user_id',
        'request',
        'response',
        'exception'
    ];

    protected $hidden = [
        'transfer_id'
    ];
}
