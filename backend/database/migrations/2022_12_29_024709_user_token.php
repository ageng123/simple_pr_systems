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
        //
        Schema::create("user_tokens", function(Blueprint $table){
            $table->bigIncrements("ut_id");
            $table->timestamps();
            $table->timestamp("token_expired");
            $table->unsignedBigInteger("uid");
            $table->text('token');
            $table->text('device_id');
            $table->foreign("uid")->references("id")->on("users");
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
