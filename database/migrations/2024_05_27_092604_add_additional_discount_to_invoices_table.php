<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalDiscountToInvoicesTable extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('additional_discount', 8, 2)->default(0)->after('discount');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('additional_discount');
        });
    }
}
