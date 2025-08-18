<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreaGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @return void
     */
    public function up()
    {
        Schema::create('area_groups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('sku');
            $table->string('country')->default('Australia');
            $table->string('meta_csv')->default('');
            $table->timestamps();
        });
        Schema::create('groups_metas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('area_group_id');
            $table->string('title')->nullable();
            $table->string('sku')->nullable();
            $table->string('postcode')->nullable();
            $table->timestamps();

            $table->foreign('area_group_id')->references('id')->on('area_groups')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('area_groups');
        Schema::dropIfExists('groups_metas');
    }
}
