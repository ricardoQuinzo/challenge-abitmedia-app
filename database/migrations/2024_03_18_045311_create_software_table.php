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
        Schema::create('software', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 10)->unique();
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->unsignedBigInteger('id_so')->nullable();
            $table->foreign('id_so')->references('id')->on('so')->onDelete('cascade');
            $table->string('serial', 100)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('software');
    }
};
