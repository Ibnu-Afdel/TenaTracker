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
        Schema::table('challenges', function (Blueprint $table) {
            $table->string('sharing_token')->nullable()->unique();
            $table->boolean('is_public')->default(false);
        });
    }

    /**
    * Reverse the migrations.
    */
    public function down(): void
    {
        Schema::table('challenges', function (Blueprint $table) {
            $table->dropColumn('sharing_token');
            $table->dropColumn('is_public');
        });
    }
};

