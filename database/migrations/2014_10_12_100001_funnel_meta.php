<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FunnelMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funnel_meta', function (Blueprint $table) {
            $table->bigInteger('post_id')->unsigned();
            $table->string('ref');
            $table->unique('post_id');
            $table->foreign('post_id')
                  ->references('id')
                  ->on('funnels')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('funnel_meta');
    }
}
