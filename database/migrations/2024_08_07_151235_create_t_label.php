<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Spatie\Permission\Models\Permission;

class CreateTLabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_label', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('aset_id')->default("0");
            $table->tinyInteger('status')->default("0");
            $table->string('updated', 50)->nullable();
            $table->string('tercetak', 50)->nullable();

            $table->timestamps();
        });

        Permission::create([
            'name' => 'cetak_label'
            ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_label');

        
        $perm = Permission::findByName("cetak_label");
        $perm->delete();

    }
}
