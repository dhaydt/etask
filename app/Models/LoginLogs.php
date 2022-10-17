<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip_user',
        'device',
        'token',
        'user_agent',
        'is_active',
        'ip_address',
    ];
}
