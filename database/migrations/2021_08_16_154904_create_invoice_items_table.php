<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained();
            $table->foreignId('shop_id')->constrained();
            $table->foreignId('user_id')->nullable();
            $table->string('name', 125);
            $table->decimal('quantity', 8, 2)->default(1);
            $table->decimal('unit_price', 8, 2)->default(0);
            $table->decimal('tax', 8, 2)->default(0);
            $table->boolean('inclusive')->default(false);
            $table->decimal('taxable_amount', 8, 2)->default(0);
            $table->decimal('tax_amount', 8, 2)->default(0);
            $table->decimal('discount', 8, 2)->default(0);
            $table->decimal('total', 8, 2)->default(0);
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
        Schema::dropIfExists('invoice_items');
    }
}
