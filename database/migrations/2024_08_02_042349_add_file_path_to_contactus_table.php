<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFilePathToContactusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contactus', function (Blueprint $table) {
            $table->string('file_path')->nullable();
        });
    }

    public function down()
    {
        Schema::table('contactus', function (Blueprint $table) {
            $table->dropColumn('file_path');
        });
    }

}
