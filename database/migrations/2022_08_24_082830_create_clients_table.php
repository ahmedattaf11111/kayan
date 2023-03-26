<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string("store_name")->nullable();
            $table->string("type")->nullable();
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->foreignId("area_id")->nullable()->constrained("areas");
            $table->string("address")->nullable();
            $table->string("location")->nullable();
            $table->boolean("same_address_shipping")->nullable();
            $table->string("shipping_address")->nullable();
            $table->foreignId('shipping_city_id')->nullable()->constrained('cities');
            $table->foreignId("shipping_area_id")->nullable()->constrained("areas");
            $table->string("shipping_location")->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
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
        Schema::dropIfExists('clients');
    }
}
