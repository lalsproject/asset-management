<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTAsetMutasiAddUniqId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_aset_mutasi', function (Blueprint $table) {
            $table->string('uniq_id',50)->nullable()->default("")->after('updated')->index();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_aset_mutasi', function($table) {
            $table->dropColumn('uniq_id');
        });
    }
}
