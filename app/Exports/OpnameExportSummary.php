<?php

namespace App\Exports;

use DB;
// use App\Model\M_Aset;
// use App\Model\M_Lokasi;
// use App\Model\M_Ruang;

use Excel;
use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;


class OpnameExportSummary implements FromView, WithEvents
{
	protected $request;

	 function __construct($request) {
            set_time_limit(600);
	        $this->request = $request;
	 }

    /**
    * @return \Illuminate\Support\Collection
    */

  	use Exportable;
    
    public function view(): View
    {
     
        $aset = $this->request['aset'];
        $kondisi = $this->request['kondisi'];
        $ruang = $this->request['ruang'];

        // dd($aset);

        // dd($opname);
        return view('excel.opnamesummary', [
            'aset' => $aset, 
            'kondisi' => $kondisi, 
            'ruang' => $ruang, 
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
              // multi cols
              $event->sheet->getStyle('A:E')->getAlignment()->setVertical('center');
              // single col
            //   $event->sheet->getStyle('D')->getAlignment()->setHorizontal('center');
            },
        ];
    }
}
