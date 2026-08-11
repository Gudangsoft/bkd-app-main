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
        // Production already has this column from an undocumented manual
        // change - this migration only exists to bring fresh installs
        // (and the test database) up to the same schema.
        if (!Schema::hasColumn('payments', 'status')) {
            Schema::table('payments', function (Blueprint $table) {
                // 0 = Ditolak, 1 = Lunas, 2 = Pending (PaymentTable::filters()).
                $table->integer('status')->default(2)->after('description');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('payments', 'status')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
