<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transfers_users';

    protected $fillable = [
        'payer',
        'payee',
        'value',
        'status',
        'transfer_protocol'
    ];
}
