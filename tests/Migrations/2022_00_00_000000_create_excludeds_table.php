<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExcludedsTable extends Migration
{
    public function up(): void
    {
        Schema::create('excludeds', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->timestamps();
            $table->string('name')->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('excludeds');
    }
}
