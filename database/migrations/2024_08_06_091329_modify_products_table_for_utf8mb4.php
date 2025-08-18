<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyProductsTableForUtf8mb4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Modify columns to support utf8mb4
            $table->text('title')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->change();
            $table->text('permalink')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Revert columns to previous state
            $table->text('title')->charset('utf8')->collation('utf8_unicode_ci')->change();
            $table->text('permalink')->charset('utf8')->collation('utf8_unicode_ci')->change();
        });
    }
}
