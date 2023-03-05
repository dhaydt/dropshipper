<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJneDestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jne_dests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('country', 20)->nullable();
            $table->string('province', 50)->nullable();
            $table->string('city', 70)->nullable();
            $table->string('district', 70)->nullable();
            $table->string('sub_district', 70)->nullable();
            $table->string('zip', 70)->nullable();
            $table->string('tarif_code', 70)->nullable();
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
        Schema::dropIfExists('jne_dests');
    }
}
