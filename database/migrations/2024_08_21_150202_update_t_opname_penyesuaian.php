<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTOpnamePenyesuaian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_opname', function (Blueprint $table) {
            $table->biginteger('lokasi_id')->nullable()->default("0")->after('device_id');
            $table->datetime('tanggal')->nullable()->default("2000-01-01")->after('ruang_id2');
            $table->string('uniq_id',50)->nullable()->default("")->after('bujur')->index();


		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_opname', function($table) {
            $table->dropColumn('lokasi_id');
            $table->dropColumn('tanggal');
            $table->dropColumn('uniq_id');
        });
    }
}
