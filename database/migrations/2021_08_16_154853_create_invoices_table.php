<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('state_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->nullable();
            $table->string('invoice_no')->unique();
            $table->date('invoice_date');
            $table->decimal('taxable', 8, 2)->default(0);
            $table->decimal('tax', 8, 2)->default(0);
            $table->decimal('discount', 8, 2)->default(0);
            $table->decimal('total', 8, 2)->default(0);
            $table->decimal('cgst', 8, 2)->default(0);
            $table->decimal('sgst', 8, 2)->default(0);
            $table->string('customer_name')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('customer_gstin')->nullable();
            $table->string('customer_pan')->nullable();
            $table->boolean('paid')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
