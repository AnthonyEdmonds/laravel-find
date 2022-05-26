<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->timestamps();
            
            $table->string('title');
            $table->unsignedTinyInteger('author_id');
            
            $table->unique(['author_id', 'title'], 'uq_author_title');
            
            $table->foreign('author_id', 'fk_book_author')
                ->references('id')
                ->on('authors')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign('fk_book_author');
        });
        
        Schema::dropIfExists('books');
    }
}
