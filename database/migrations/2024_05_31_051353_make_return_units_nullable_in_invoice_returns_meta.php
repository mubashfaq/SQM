<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MakeReturnUnitsNullableInInvoiceReturnsMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE invoice_returns_meta MODIFY return_units INTEGER NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE invoice_returns_meta MODIFY return_units INTEGER NOT NULL;');
    }
}
