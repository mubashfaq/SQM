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
        Schema::table('order_metas', function (Blueprint $table) {
        $table->dropColumn('quantity');
         });
        Schema::table('order_metas', function (Blueprint $table) {
            $table->decimal('quantity', 10, 4)->nullable(); // 4 decimal places (store up to 0.0001)

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_metas', function (Blueprint $table) {
            //
        });
    }
};
