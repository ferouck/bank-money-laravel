<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferUser extends Model
{
    use HasFactory;

    protected $table = 'transfers_users';

    protected $fillable = [
        'payer',
        'payee',
        'value',
        'status',
        'transfer_protocol'
    ];
}
