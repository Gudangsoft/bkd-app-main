<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // The "Add User" form (asesor status) and assessor_fees table both use
        // 'external' / 'external_dif', but the enum only allowed 'eksternal'.
        // Saving an asesor with those statuses threw a SQL error under strict
        // mode, so the insert silently never happened.
        DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('user', 'internal', 'eksternal', 'external', 'external_dif', 'serdos', 'non-serdos') NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('user', 'internal', 'eksternal', 'serdos', 'non-serdos') NULL");
    }
};
