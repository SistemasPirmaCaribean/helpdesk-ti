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
        Schema::create('tkt_ticket_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idticket')->constrained('tkt_tickets')->onDelete('cascade');
            $table->foreignId('iduser')->constrained('usuarios')->onDelete('cascade');
            $table->string('comment', 1000);
            $table->boolean('seen')->default(false); // saber si el mensaje fue visto
            $table->timestamp('seen_at')->nullable(); // fecha cuando se vio el mensaje
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tkt_ticket_comments');
    }
};
