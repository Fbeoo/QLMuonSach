<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_history_rent_book', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->date('expiration_date');
            $table->integer('quantity');
            $table->date('return_date')->nullable();
            $table->integer('status');
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('history_rent_book_id');
            $table->foreign('book_id')->references('id')->on('book');
            $table->foreign('history_rent_book_id')->references('id')->on('history_rent_book');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_history_rent_book');
    }
};
