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
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained();
            $table->foreignUuid('service_vendor_id')->nullable()->constrained();
            $table->foreignUuid('entity_id')->nullable()->constrained();
            $table->foreignUuid('patient_id')->nullable()->constrained();
            $table->foreignUuid('tipo_nota_id')->nullable()->constrained();
            $table->string('type')->nullable();
            $table->string('typeable_type')->nullable();
            $table->string('typeable_id')->nullable();
            $table->string('path_json')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('radication_number')->nullable();
            $table->string('note_number')->nullable();
            $table->decimal('value_glosa', 15, 2)->nullable();
            $table->decimal('value_paid', 15, 2)->nullable();
            $table->date('invoice_date')->nullable();
            $table->date('radication_date')->nullable();
            $table->decimal('total', 15, 2)->nullable();
            $table->decimal('remaining_balance', 15, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
