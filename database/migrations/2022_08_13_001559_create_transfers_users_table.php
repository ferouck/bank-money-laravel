<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payer');
            $table->foreignId('payee');
            $table->float('value', 10, 2);
            $table->enum('status', ['pending', 'fulfilled'])->default('pending');
            $table->uuid('transfer_protocol');

            $table->foreign('payer')->references('id')->on('users');
            $table->foreign('payee')->references('id')->on('users');

            $table->index('transfer_protocol');
            $table->unique('transfer_protocol');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfers_users');
    }
};
