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

            $table->id();

            $table->string('invoice_number', 50)->unique();
            $table->date('invoice_date')->nullable();
            $table->date('due_date')->nullable();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnUpdate();

            $table->foreignId('section_id')
                ->constrained()
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->decimal('amount_collection', 10, 3)->nullable();
            $table->decimal('amount_commission', 10, 3);
            $table->decimal('total_with_value_vat', 10, 3);
            $table->decimal('remaining_amount', 10, 3)->nullable();
            $table->decimal('amount_paid', 10, 3)->nullable();
            $table->decimal('discount', 10, 2);
            $table->decimal('value_vat', 10, 2);
            $table->string('rate_vat', 999);
            $table->decimal('total', 10, 2);

            $table->string('status', 50)->default('غير مدفوعة');
            $table->unsignedTinyInteger('value_status')->default(2);

            $table->text('note')->nullable();
            $table->date('payment_date')->nullable();

            $table->softDeletes();
            $table->timestamps();
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
