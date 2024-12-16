<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

class CreateTAsetMutasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_aset_mutasi', function (Blueprint $table) {
            $table->id();
            $table->biginteger('aset_id')->nullable()->default("0");
            $table->biginteger('b_ruang_id')->nullable()->default("0")->comment("before ruang_id");
            $table->biginteger('a_ruang_id')->nullable()->default("0")->comment("after ruang_id");
            $table->string('b_kode',50)->nullable()->default("")->comment("before kode");
            $table->string('a_kode',50)->nullable()->default("")->comment("after kode");
            $table->string('updated',50)->nullable()->default("")->comment("user updated");
            $table->timestamps();
        });

        Permission::create([
            'name' => 'aset_mutasi'
            ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_aset_mutasi');

        $perm = Permission::findByName("aset_mutasi");
        $perm->delete();

    }
}
