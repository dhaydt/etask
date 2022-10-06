<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dasar extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'id',
        'dasar',
        'keterangan',
        'status',
    ];
}
