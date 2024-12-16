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

class OpnameExport implements FromView, WithEvents
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
     
        $kondisi = $this->request['kondisi'];
        $opname = $this->request['opname'];
        $ruang = $this->request['ruang'];

        // dd($opname);
        return view('excel.opname', [
            'opname' => $opname, 
            'kondisi' => $kondisi, 
            'ruang' => $ruang, 
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                /** @var Worksheet $sheet */
                foreach ($event->sheet->getColumnIterator('J') as $row) {
                    foreach ($row->getCellIterator() as $cell) {
                        if ($cell->getValue() != "") {

                            if (str_contains($cell->getValue(), '://')) {
                                $cell->setHyperlink(new Hyperlink($cell->getValue(), 'Read'));
    
                                 // Upd: Link styling added
                                 $event->sheet->getStyle($cell->getCoordinate())->applyFromArray([
                                    'font' => [
                                        'color' => ['rgb' => '0000FF'],
                                        'underline' => 'single'
                                    ]
                                ]);
                            }
                        }
                    }
                }
            },
        ];
    }
}
