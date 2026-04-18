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
        Schema::create('tkt_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idticket')->constrained('tkt_tickets')->onDelete('cascade');
            $table->foreignId('iduser')->constrained('usuarios')->onDelete('cascade');
            $table->string('action', 255);
            $table->string('field', 100)->nullable();
            $table->string('old_value', 255)->nullable();
            $table->string('new_value', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tkt_logs');
    }
};
