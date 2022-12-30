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
        Schema::create("organizations", function(Blueprint $table){
            $table->bigIncrements("organization_id");
            $table->string("organization_code", 255)->nullable();
            $table->unsignedBigInteger("organization_parent")->nullable();
            $table->string("organization_name")->nullable();
            $table->string("organization_short_name")->nullable();
            $table->unsignedInteger("organization_status")->default(1);
            $table->text("organization_description")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create("areas", function(Blueprint $table){
            $table->bigIncrements("area_id");
            $table->timestamps();
            $table->softDeletes();
            $table->string("area_code")->nullable();
            $table->string("area_name");
            $table->text("area_description")->nullable();
            $table->string("lat")->nullable();
            $table->string("long")->nullable();
        });
        Schema::create("units", function(Blueprint $table){
            $table->bigIncrements("unit_id");
            $table->timestamps();
            $table->softDeletes();
            $table->string("unit_name");
            $table->text("unit_descriptions");
            $table->string("unit_code")->nullable();
        });
        Schema::create("job_positions", function(Blueprint $table){
            $table->bigIncrements("jp_id");
            $table->timestamps();
            $table->softDeletes();
            $table->string("job_position_name");
            $table->text("job_position_description")->nullable();
            $table->string("job_position_level")->nullable();
        });
        Schema::create("employee_structures", function(Blueprint $table){
            $table->bigIncrements("structural_id");
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger("employee_id");
            $table->unsignedBigInteger("organization_id")->nullable();
            $table->unsignedBigInteger("job_position_id")->nullable();
            $table->unsignedBigInteger("unit_id")->nullable();
            $table->unsignedBigInteger("area_id")->nullable();
            $table->unsignedBigInteger("direct_parent")->nullable();
            $table->foreign("employee_id")->references('id')->on('users');
            $table->foreign("organization_id")->references("organization_id")->on("organizations");
            $table->foreign("unit_id")->references("unit_id")->on("units");
            $table->foreign("job_position_id")->references('jp_id')->on('job_positions');
            $table->foreign("area_id")->references("area_id")->on("areas");
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
        Schema::dropIfExists("employee_structures");
        Schema::dropIfExists('units');
        Schema::dropIfExists("areas");
        Schema::dropIfExists("organizations");
        Schema::dropIfExists("job_positions");

    }
};
