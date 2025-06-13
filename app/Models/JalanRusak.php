<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JalanRusak extends Model
{
	protected $table = 'jalan_rusak';

	protected $fillable = [
		'deskripsi',
		'longitude',
		'latitude',
		'tingkat_keparahan',
		'sudah_diperbaiki',
	];

	protected $casts = [
		'longitude' => 'float',
		'latitude' => 'float',
		'tingkat_keparahan' => 'string',
		'sudah_diperbaiki' => 'boolean',
	];

	public function foto(): HasMany
	{
		return $this->hasMany(FotoJalanRusak::class);
	}
}
