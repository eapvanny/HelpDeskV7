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
        Schema::table('departments', function (Blueprint $table) {
            if (!Schema::hasColumn('departments', 'code')) {
                $table->string('code')->after('id');
            }
            if (!Schema::hasColumn('departments', 'name_in_latin')) {
                $table->string('name_in_latin')->nullable()->after('name');
            }
            if (!Schema::hasColumn('departments', 'abbreviation')) {
                $table->string('abbreviation')->nullable()->after('name_in_latin');
            }
            if (!Schema::hasColumn('departments', 'deleted_at')) {
                $table->softDeletes();
            }
            if (!Schema::hasColumn('departments', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable();
            }
            if (!Schema::hasColumn('departments', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable();
            }
            if (!Schema::hasColumn('departments', 'deleted_by')) {
                $table->unsignedBigInteger('deleted_by')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            if (Schema::hasColumn('departments', 'code')) {
                $table->dropColumn('code');
            }
            if (Schema::hasColumn('departments', 'name_in_latin')) {
                $table->dropColumn('name_in_latin');
            }
            if (Schema::hasColumn('departments', 'abbreviation')) {
                $table->dropColumn('abbreviation');
            }
            if (Schema::hasColumn('departments', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
            if (Schema::hasColumn('departments', 'created_by')) {
                $table->dropColumn('created_by');
            }
            if (Schema::hasColumn('departments', 'updated_by')) {
                $table->dropColumn('updated_by');
            }
            if (Schema::hasColumn('departments', 'deleted_by')) {
                $table->dropColumn('deleted_by');
            }
        });
    }
};
