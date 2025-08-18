<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePriceInOrderMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_metas', function (Blueprint $table) {
            $table->string('price')->nullable()->change(); // Make the column nullable
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_metas', function (Blueprint $table) {
            $table->bigInteger('price')->nullable(false)->change(); // Revert to the original type and make it non-nullable
        });
    }
}
