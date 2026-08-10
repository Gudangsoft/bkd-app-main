<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nidn')->nullable()->after('is_active');
            $table->string('progdi')->nullable()->after('is_active');
            $table->integer('assessor_fee')->nullable()->after('is_active');
            $table->enum('status', ['user', 'internal', 'eksternal', 'serdos', 'non-serdos'])->nullable()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('table', function (Blueprint $table) {
            //
        });
    }
};
