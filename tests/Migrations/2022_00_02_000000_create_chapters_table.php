<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChaptersTable extends Migration
{
    public function up(): void
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->timestamps();

            $table->string('title');
            $table->unsignedTinyInteger('number');
            $table->unsignedTinyInteger('book_id');

            $table->unique(['book_id', 'number'], 'uq_book_chapter');

            $table->foreign('book_id', 'fk_chapter_book')
                ->references('id')
                ->on('books')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->dropForeign('fk_chapter_book');
        });

        Schema::dropIfExists('chapters');
    }
}
