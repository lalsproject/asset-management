<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Model\M_JenisMaintenance;

class CreateMJenisMaintenance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_jenis_maintenance', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('deskripsi', 100)->default("")->unique();
            $table->string('updated', 50)->nullable();
            $table->timestamps();
        });

        M_JenisMaintenance::Create([
                    'deskripsi' => 'Service Berkala',
                    'updated' => 'SEED',
                ]);

        M_JenisMaintenance::Create([
                    'deskripsi' => 'Service Ringan',
                    'updated' => 'SEED',
                ]);

        M_JenisMaintenance::Create([
                    'deskripsi' => 'Service Besar',
                    'updated' => 'SEED',
                ]);

        M_JenisMaintenance::Create([
                    'deskripsi' => 'Penggantian Komponen',
                    'updated' => 'SEED',
                ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('m_jenis_maintenance');
    }
}
