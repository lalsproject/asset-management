<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


use Spatie\Permission\Models\Permission;

class CreateTApi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_key', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('token', 70);
            $table->string('deskripsi', 100)->default("");
            $table->tinyInteger('aktif')->default("1");
            $table->string('updated', 50)->nullable();
            $table->timestamps();
        });

        Permission::create([
            'name' => 'master_api_key'
            ]);    


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_key');

        $perm = Permission::findByName("master_api_key");
        $perm->delete();

    }
}