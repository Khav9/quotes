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
        Schema::create('emoji', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('avatar'); // show in navbar for vatar image
            $table->string('profile'); // show in profile page
            $table->string('micro'); // show in each quote
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emoji');
    }
};
