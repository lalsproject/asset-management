<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Fungsi\Project;

use Illuminate\Support\Facades\Mail;
use DB;

class backup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backupdb';

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

      
        $x = Project::backupdb();
      
        $this->info("Selesai ");


    }
}
