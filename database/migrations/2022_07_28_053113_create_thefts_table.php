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
        Schema::create('thefts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sleeper_id')->constrained('parties')->cascadeOnDelete();
            $table->foreignId('theft_id')->constrained('parties')->cascadeOnDelete();
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
        Schema::dropIfExists('thefts');
    }
};
