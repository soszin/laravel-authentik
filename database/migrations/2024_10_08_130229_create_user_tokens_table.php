<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(config('authentik.table_name'), function (Blueprint $table) {
            $table->id(); // Klucz główny
            $table->unsignedBigInteger('user_id'); // ID użytkownika powiązane z tokenem
            $table->string('session_id', 255)->unique();
            $table->string('device_id', 255)->unique();
            $table->text('access_token'); // Token dostępowy OAuth
            $table->text('refresh_token')->nullable(); // Token odświeżania, jeśli jest używany
            $table->string('token_type')->default('Bearer'); // Typ tokenu, zwykle 'Bearer'
            $table->text('scopes')->nullable(); // Zakresy OAuth, np. 'profile email'
            $table->timestamp('expires_at')->nullable(); // Czas wygaśnięcia tokenu dostępowego
            $table->timestamp('refresh_token_expires_at')->nullable(); // Czas wygaśnięcia tokenu odświeżania (jeśli jest)
            $table->timestamps(); // Czas utworzenia i aktualizacji rekordu

            // Relacja z tabelą users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('authentik.table_name'));
    }
};
