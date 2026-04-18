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
        Schema::create('tkt_departament_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iddepartament');
            $table->unsignedBigInteger('iduser');
            $table->foreign('iddepartament')->references('id')->on('tkt_departaments')->onDelete('cascade');
            $table->foreign('iduser')->references('id')->on('usuarios')->onDelete('cascade');
            $table->unique(['iddepartament', 'iduser']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tkt_departament_users');
    }
};
