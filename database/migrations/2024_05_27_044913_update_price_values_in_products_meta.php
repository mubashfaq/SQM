<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdatePriceValuesInProductsMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update value column to 110% of the current value where option ends with _price and value is not 0
        DB::table('products_meta')
            ->where('option', 'like', '%_price')
            ->where('value', '>', 0)
            ->update([
                'value' => DB::raw('value * 1.10')
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert the value column to 100% of the updated value where option ends with _price and value is not 0
        DB::table('products_meta')
            ->where('option', 'like', '%_price')
            ->where('value', '>', 0)
            ->update([
                'value' => DB::raw('value / 1.10')
            ]);
    }
}
