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
        Schema::create('author_book', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('author_id');
            $table->foreign('book_id')->references('id')->on('book');
            $table->foreign('author_id')->references('id')->on('author_info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('author_book');
    }
};
