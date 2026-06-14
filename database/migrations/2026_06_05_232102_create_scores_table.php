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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('subject_id')->constrained('subjects');
            $table->integer('tugas1')->nullable();
            $table->integer('tugas2')->nullable();
            $table->integer('asts')->nullable();
            $table->integer('tugas4')->nullable();
            $table->integer('tugas5')->nullable();
            $table->integer('asas')->nullable();
            $table->string('pg_asas')->nullable();
            $table->integer('n1')->nullable();
            $table->integer('n2')->nullable();
            $table->integer('n3')->nullable();
            $table->integer('n4')->nullable();
            $table->integer('n5')->nullable();
            $table->integer('nilai_essai')->nullable();
            $table->integer('murni_asas_genap')->nullable();
            $table->integer('perbaikan')->nullable();
            $table->enum('status', ['tuntas', 'belum'])->nullable();
            $table->integer('nilai_akhir')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
