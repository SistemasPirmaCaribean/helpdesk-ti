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
        Schema::create('tkt_category_technicians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idcategory');
            $table->unsignedBigInteger('idtechnician');
            $table->foreign('idcategory')->references('id')->on('tkt_categories')->onDelete('cascade');
            $table->foreign('idtechnician')->references('id')->on('usuarios')->onDelete('cascade');
            $table->unique('idcategory'); // Solo un tecnico por categoria
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tkt_category_technicians');
    }
};
