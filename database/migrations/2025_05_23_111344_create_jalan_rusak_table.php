<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('jalan_rusak', function (Blueprint $table) {
            $table->id();
            $table->text('deskripsi');
            $table->string('foto');
            $table->decimal('longitude', 10, 7);
            $table->decimal('latitude', 10, 7);
            $table->enum('tingkat_keparahan', ['ringan', 'sedang', 'berat']);
            $table->boolean('sudah_diperbaiki')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jalan_rusak');
    }
};