<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Storage;

use App\Model\M_Kondisi;

class ReportOpname extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        // try {
            $r = $this->data;

            $file_path = Storage::disk('local')->path("opnameaset.zip");

            $kondisi = M_Kondisi::pluck('deskripsi','id');
            $ruang_status = [1 => "Sesuai Ruang", 2 => "Berbeda Ruang"];
    

            return $this->from($address = env('MAIL_FROM_ADDRESS', 'noreplay@asetmanagement.mmm'), $name = env('MAIL_FROM_NAME', 'Aset Management'))
                   ->subject('Report Opname ')
                   ->view('email.report.opname', compact('r','kondisi','ruang_status'))
                   ->attach($file_path);;
    
            //code...
        // } catch (\Throwable $th) {
        //     dd("error");
        //     //throw $th;
        // }
        
    }
}
