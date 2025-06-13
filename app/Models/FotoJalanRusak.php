<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FotoJalanRusak extends Model
{
	protected $table = 'foto_jalan_rusak';

	protected $fillable = [
		'jalan_rusak_id',
		'foto',
	];

	public function jalanRusak(): BelongsTo
	{
		return $this->belongsTo(JalanRusak::class);
	}
}
