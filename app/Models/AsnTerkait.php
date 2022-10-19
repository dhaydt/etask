<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsnTerkait extends Model
{
    use HasFactory;

    protected $table = 'asn_terkait';

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }
}
