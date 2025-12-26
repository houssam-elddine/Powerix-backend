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
        Schema::create('inscirptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('cour_id')->constrained('cours')->cascadeOnDelete();
            $table->foreignId('abonnement_id')->constrained('abonnements')->cascadeOnDelete();
            $table->date('date_inscription');
            $table->enum('etat',['en attente' , 'sans payée' , 'valider' , 'annuler'])->default('en attente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscirptions');
    }
};
