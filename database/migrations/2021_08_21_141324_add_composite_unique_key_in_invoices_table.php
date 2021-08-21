<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompositeUniqueKeyInInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->unique(['invoice_no', 'shop_id'], 'invoices_invoice_no_shop_id_unique');
            $table->dropUnique('invoices_invoice_no_unique');
        });
    }
}
