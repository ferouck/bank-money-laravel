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
        Schema::create('transfers_users_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->json('request');
            $table->json('response');
            $table->longText('exception')->nullable(true);
            $table->foreignUuid('transfer_id');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('transfer_id')->references('transfer_protocol')->on('transfers_users');
            
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
        Schema::dropIfExists('transfers_users_logs');
    }
};
