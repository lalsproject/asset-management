<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Model\M_Satuan;

class CreateMSatuan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_satuan', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('deskripsi', 100)->default("")->unique();
            $table->string('updated', 50)->nullable();
            $table->timestamps();
        });

        // M_Satuan::Create([
        //             'deskripsi' => 'Unit',
        //             'updated' => 'SEED',
        //         ]);

        // M_Satuan::Create([
        //             'deskripsi' => 'Pcs',
        //             'updated' => 'SEED',
        //         ]);

        // M_Satuan::Create([
        //             'deskripsi' => 'Set',
        //             'updated' => 'SEED',
        //         ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('m_satuan');
    }
}
