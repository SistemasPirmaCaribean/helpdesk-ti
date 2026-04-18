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
        Schema::create('tkt_ticket_claims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idticket');
            $table->unsignedBigInteger('iduser');
            $table->string('type'); // 'claim' o 'unclaim'
            $table->string('status')->default('pending'); // 'pending', 'approved', 'rejected'
            $table->text('reason')->nullable(); // Razón para reclamar o desasignar
            $table->foreign('idticket')->references('id')->on('tkt_tickets')->onDelete('cascade');
            $table->foreign('iduser')->references('id')->on('usuarios')->onDelete('cascade');
            $table->boolean('active')->default(true); // Para marcar la reclamación como activa o inactiva
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tkt_ticket_claims');
    }
};
