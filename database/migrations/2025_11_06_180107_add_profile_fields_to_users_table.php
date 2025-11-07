<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone'))          $table->string('phone', 25)->nullable()->after('email');
            if (!Schema::hasColumn('users', 'dni'))            $table->string('dni', 20)->nullable()->unique()->after('phone');
            if (!Schema::hasColumn('users', 'first_name'))     $table->string('first_name', 191)->nullable()->after('dni');
            if (!Schema::hasColumn('users', 'last_name1'))     $table->string('last_name1', 191)->nullable()->after('first_name');
            if (!Schema::hasColumn('users', 'last_name2'))     $table->string('last_name2', 191)->nullable()->after('last_name1');
            if (!Schema::hasColumn('users', 'birth_date'))     $table->date('birth_date')->nullable()->after('last_name2');
            if (!Schema::hasColumn('users', 'gender'))         $table->string('gender', 40)->nullable()->after('birth_date');
            if (!Schema::hasColumn('users', 'education_level'))$table->string('education_level', 60)->nullable()->after('gender');
            if (!Schema::hasColumn('users', 'eu_resident'))    $table->boolean('eu_resident')->default(false)->after('education_level');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['phone','dni','first_name','last_name1','last_name2','birth_date','gender','education_level','eu_resident'] as $col) {
                if (Schema::hasColumn('users', $col)) $table->dropColumn($col);
            }
        });
    }
};
