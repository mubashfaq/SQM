<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class AddOrderIdToContactusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contactus', function (Blueprint $table) {
            $table->string('order_id')->nullable()->after('id');
            $table->string('f_name')->nullable()->change();
            $table->string('l_name')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('subject')->nullable()->change();
            $table->text('message')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contactus', function (Blueprint $table) {
            $table->dropColumn('order_id');
            $table->string('f_name')->nullable(false)->change();
            $table->string('l_name')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
            $table->string('subject')->nullable(false)->change();
            $table->text('message')->nullable(false)->change();

        });
    }
}
