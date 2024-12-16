<?php

namespace App\Console\Commands;

Use App\Http\Controllers\Api\V1\SyncController;
use App\Http\Controllers\Api\V1\TransaksiController;

use Illuminate\Console\Command;


use App\Th_Jual;
use App\Data;
use App\Helpers;
use App\Guzzle;
use App\Fungsi\Crm;


use Illuminate\Support\Facades\Mail;
use ZipArchive;

class testzip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testzip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Script Function';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $this->info("Test Zip ");


        $zip = new ZipArchive;
        $res = $zip->open(env("LOKASI_UPLOAD")."xxx.zip");
        if ($res === TRUE) {
          $zip->extractTo(env("LOKASI_UPLOAD").'extract_path');
          $zip->close();
          $this->info("Berhasil ");
        } else {
            $this->info("Gagal ");
        }

        // $myfile = fopen(env("LOKASI_UPLOAD")."/data2".$kodecabang.".web", "r") or die("Unable to open file!");
        // $bodyContent = fread($myfile,filesize(env("LOKASI_UPLOAD")."/data2".$kodecabang.".web"));
        // fclose($myfile);


    }
}
