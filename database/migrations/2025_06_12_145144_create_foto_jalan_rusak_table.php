<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
	public function up(): void
	{
		Schema::create('foto_jalan_rusak', function (Blueprint $table) {
			$table->id();
			$table->foreignId('jalan_rusak_id')->constrained('jalan_rusak')->onDelete('cascade');
			$table->string('foto');
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('foto_jalan_rusak');
	}
};
