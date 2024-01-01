<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class TimbangansExport implements FromView, WithColumnWidths, WithDefaultStyles, WithDrawings
{
    public $data;

    public function __construct($data) {
        $this->data = $data;  
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return $defaultStyle->getFill()->setFillType(Fill::FILL_SOLID);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 23,            
            'C' => 9,         
            'D' => 9,         
            'E' => 9,         
            'F' => 9,         
            'G' => 9,         
            'H' => 9,         
            'I' => 9,         
            'J' => 9,         
            'K' => 9,         
            'L' => 9,         
            'M' => 9,         
            'N' => 14         
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('logo');
        $drawing->setPath(public_path('images/logo.png'));
        $drawing->setHeight(80);
        $drawing->setOffsetX(22);
        $drawing->setCoordinates('B2');

        return $drawing;
    }

    public function view(): View
    {
        return view('web.timbangan.excel', $this->data);
    }
}
