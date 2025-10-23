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
        \DB::statement("ALTER TABLE reclamations 
            MODIFY COLUMN statut ENUM('en_attente', 'en_cours', 'traitée', 'resolue', 'rejetee') 
            NOT NULL DEFAULT 'en_attente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // On ne peut pas vraiment annuler cette migration car on ne connaît pas l'état précédent
        // On laisse vide car c'est une migration de correction
    }
};
