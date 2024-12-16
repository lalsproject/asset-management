<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTMaintenance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_maintenance', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('aset_id')->unsigned();
            $table->integer('jenis_maintenance_id')->unsigned();
            $table->string('keterangan', 200)->default("");
            $table->string('vendor', 50)->default("");
            $table->double('harga')->nullable();
            $table->string('updated', 50)->nullable();
            $table->timestamps();

            // $table->foreign('aset_id')
            //     ->references('id')
            //     ->on('m_aset')
            //     ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_maintenance');
    }
}
