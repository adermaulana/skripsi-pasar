<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->tinyInteger('rate_jenisproduk')->default(0);
            $table->tinyInteger('rate_ketersediaanproduk')->default(0);
            $table->tinyInteger('rate_pelayanan')->default(0);
            $table->tinyInteger('rate_kebersihantoko')->default(0);
            $table->tinyInteger('rate_kualitasproduk')->default(0);
            $table->tinyInteger('rate_jumlahpenjualan')->default(0);
            $table->text('review')->nullable();
            $table->enum('status',['active','inactive'])->default('active');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('SET NULL');
            $table->foreign('store_id')->references('id')->on('users')->onDelete('SET NULL');
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
        Schema::dropIfExists('product_reviews');
    }
}
