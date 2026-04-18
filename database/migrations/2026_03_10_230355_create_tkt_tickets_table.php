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
        Schema::create('tkt_tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('idcia')->unsigned();
            $table->foreignId('idstatus')->constrained('tkt_statuses')->onDelete('cascade');
            $table->foreignId('idcategory')->constrained('tkt_categories')->onDelete('cascade');
            $table->foreignId('idpriority')->constrained('tkt_priorities')->onDelete('cascade');
            $table->foreignId('iduser')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('idtechnician')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->string('reference_number', 255)->unique();
            $table->string('title', 255);
            $table->string('description', 1000);
            $table->dateTime('date_opened');
            $table->dateTime('date_closed')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tkt_tickets');
    }
};
