<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('questions') && !Schema::hasColumn('questions', 'sort_order')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->integer('sort_order')->default(0)->after('question_text');
            });

            DB::statement("UPDATE `questions` SET `sort_order` = `order` WHERE `sort_order` = 0 AND `order` > 0");
        }

        if (Schema::hasTable('question_options') && !Schema::hasColumn('question_options', 'sort_order')) {
            Schema::table('question_options', function (Blueprint $table) {
                $table->integer('sort_order')->default(0)->after('option_text');
            });

            DB::statement("UPDATE `question_options` SET `sort_order` = `order` WHERE `sort_order` = 0 AND `order` > 0");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('questions') && Schema::hasColumn('questions', 'sort_order')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }

        if (Schema::hasTable('question_options') && Schema::hasColumn('question_options', 'sort_order')) {
            Schema::table('question_options', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
    }
};
