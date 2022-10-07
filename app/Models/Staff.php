<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    public function detail()
    {
        return $this->belongsTo(StaffDetail::class, 'id', 'id_staff');
    }
}
