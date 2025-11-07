<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cv', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('participant_id');
            $table->string('filename', 200);
            $table->string('mime', 100)->default('application/pdf');
            $table->binary('data'); // LONGBLOB en MySQL
            $table->boolean('is_primary')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('participant_id')
                ->references('id')->on('participants')
                ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('cv');
    }
};
