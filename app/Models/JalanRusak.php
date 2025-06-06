<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JalanRusak extends Model
{
    protected $table = 'jalan_rusak';

    protected $fillable = [
        'deskripsi',
        'longitude',
        'latitude',
        'tingkat_keparahan',
        'foto',
        'sudah_diperbaiki',
    ];

    protected $casts = [
        'longitude' => 'float',
        'latitude' => 'float',
        'tingkat_keparahan' => 'string',
        'sudah_diperbaiki' => 'boolean',
    ];
}
