<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('decreto780de2026s', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('codigo');
            $table->text('descripcion');
            $table->string('grupo');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decreto780de2026s');
    }
};
