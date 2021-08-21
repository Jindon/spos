<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('name', 125);
            $table->string('address');
            $table->string('phone', 15)->nullable();
            $table->string('alt_phone', 15)->nullable();
            $table->string('email')->nullable();
            $table->string('gstin', 15)->nullable();
            $table->string('bank_name', 125)->nullable();
            $table->string('bank_branch', 125)->nullable();
            $table->string('bank_ifsc', 15)->nullable();
            $table->string('bank_account', 50)->nullable();
            $table->foreignId('state_id')->constrained();
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
        Schema::dropIfExists('branches');
    }
}
