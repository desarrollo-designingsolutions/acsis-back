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
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained();
            $table->foreignUuid('invoice_id')->constrained();
            $table->string('type');
            $table->string('serviceable_type')->nullable();
            $table->string('serviceable_id')->nullable();
            $table->string('codigo_servicio')->nullable();
            $table->string('nombre_servicio')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('unit_value', 15, 2)->default(0);
            $table->decimal('total_value', 15, 2)->default(0);
            $table->decimal('value_glosa', 15, 2)->default(0);
            $table->decimal('value_approved', 15, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
