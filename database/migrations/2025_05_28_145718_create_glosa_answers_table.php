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
        Schema::create('glosa_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained();
            $table->foreignUuid('user_id')->constrained();
            $table->foreignUuid('glosa_id')->constrained();
            $table->text('observation')->nullable();
            $table->date('date_answer');
            $table->string('file')->nullable();
            $table->decimal('value_approved', 15, 2)->nullable();
            $table->decimal('value_accepted', 15, 2)->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('glosa_answers');
    }
};
