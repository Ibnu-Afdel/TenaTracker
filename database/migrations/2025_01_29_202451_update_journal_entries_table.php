<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            // Make content nullable and add new columns
            $table->text('content')->nullable()->change();
            $table->json('blocks')->nullable()->after('content');
            $table->json('tags')->nullable()->after('blocks');
            
            // Drop old columns if they exist
            if (Schema::hasColumn('journal_entries', 'image')) {
                $table->dropColumn('image');
            }
            if (Schema::hasColumn('journal_entries', 'code_snippet')) {
                $table->dropColumn('code_snippet');
            }
        });
    }

    public function down(): void
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            // Restore original state
            $table->text('content')->nullable(false)->change();
            $table->dropColumn(['blocks', 'tags']);
            $table->string('image')->nullable();
            $table->text('code_snippet')->nullable();
        });
    }
};
