<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pdf_contrato', function (Blueprint $table) {
            $table->id();

            // ⚠️ Debe coincidir el tipo con contracts.id (normalmente BIGINT UNSIGNED en Laravel)
            $table->unsignedBigInteger('contract_id')->unique();

            $table->string('filename', 200);
            $table->string('mime', 100)->default('application/pdf');
            $table->longBlob('data'); // LONGBLOB en MySQL
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('contract_id')
                  ->references('id')->on('contracts')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('pdf_contrato');
    }
};
