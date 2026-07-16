<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pakai raw statement supaya tidak butuh package doctrine/dbal
        DB::statement('ALTER TABLE teachers CHANGE nip nuptk VARCHAR(50) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE teachers CHANGE nuptk nip VARCHAR(50) NULL');
    }
};
