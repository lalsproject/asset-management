<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Model\M_Lokasi;


class CreateMLokasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_lokasi', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('kode', 50)->default("")->unique();
            $table->string('deskripsi', 100)->default("")->unique();
            $table->string('updated', 50)->nullable();
            $table->timestamps();
        });

        // M_Lokasi::Create([
        //     'kode' => 'HO',
        //     'deskripsi' => 'Head Office',
        //     'updated' => 'SEED',
        // ]);






    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('m_lokasi');
    }
}
