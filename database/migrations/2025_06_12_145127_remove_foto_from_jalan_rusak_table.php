<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
	public function up(): void
	{
		Schema::table('jalan_rusak', function (Blueprint $table) {
			$table->dropColumn('foto');
		});
	}

	public function down(): void
	{
		Schema::table('jalan_rusak', function (Blueprint $table) {
			$table->string('foto')->after('deskripsi');
		});
	}
};
