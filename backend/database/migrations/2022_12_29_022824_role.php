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
        Schema::create("roles", function(Blueprint $table){
            $table->bigIncrements("role_id");
            $table->timestamps();
            $table->string("role_name")->unique();
            $table->text("description")->nullable();
            $table->unsignedInteger("is_active")->default(0);
            $table->softDeletes();
        });
        Schema::create("role_users", function(Blueprint $table){
            $table->bigIncrements("ru_id");
            $table->timestamps();
            $table->unsignedBigInteger("role_id");
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("role_id")->references("role_id")->on("roles");
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
        Schema::dropIfExists("role_users");
        Schema::dropIfExists("roles");
    }
};
