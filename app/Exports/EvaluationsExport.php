<?php

namespace App\Exports;

use App\Models\Evaluation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class EvaluationsExport implements FromCollection, ShouldAutoSize, WithStyles, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $competition,$columns;

    function __construct(){
        
    }
    
    private $result = [];
    public function set_export_data($result){
        $this->result = $result;
    }

    public function collection(){
        return collect($this->result);
    }

    public function styles($sheet)
    {
        return [
            // 'A1' => ['font' => ['bold' => true]],
            // 'B11' => ['font' => ['bold' => true]],
            // 'B18' => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function( AfterSheet $event){
                // Title
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(30);
                $event->sheet->getDelegate()->mergeCells('A1:B1');
                $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A1:B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A1:B1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $event->sheet->getDelegate()->getStyle('A2')->getFont()->setBold(true)->setSize(14);
                $event->sheet->getDelegate()->mergeCells('A2:B2');
                $event->sheet->getDelegate()->getStyle('A2:B2')->getFill()->setFillType(Fill::FILL_SOLID);
                $event->sheet->getDelegate()->getStyle('A2:B2')->getFill()->getStartColor()->setARGB('FFFF00');

                // group 1
                $event->sheet->getDelegate()->getStyle('A2:B13')->getFont()->setSize(13);
                $event->sheet->getDelegate()->getStyle('A3:B3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
                $event->sheet->getDelegate()->getStyle('A4:B13')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $event->sheet->getDelegate()->getStyle('B12')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('A12:B13')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A12:B13')->getFill()->setFillType(Fill::FILL_SOLID);
                $event->sheet->getDelegate()->getStyle('A12:B13')->getFill()->getStartColor()->setARGB('FFFF00');

                // group 2
                $event->sheet->getDelegate()->getStyle('A15:B22')->getFont()->setSize(13);
                $event->sheet->getDelegate()->getStyle('A15:B15')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
                $event->sheet->getDelegate()->getStyle('A16:B22')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $event->sheet->getDelegate()->getStyle('B21')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('A21:B22')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A21:B22')->getFill()->setFillType(Fill::FILL_SOLID);
                $event->sheet->getDelegate()->getStyle('A21:B22')->getFill()->getStartColor()->setARGB('FFFF00');

                // group 3
                $event->sheet->getDelegate()->getStyle('A24:B30')->getFont()->setSize(13);
                $event->sheet->getDelegate()->getStyle('A24:B24')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
                $event->sheet->getDelegate()->getStyle('A25:B30')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $event->sheet->getDelegate()->getStyle('B29')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('A29:B30')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A29:B30')->getFill()->setFillType(Fill::FILL_SOLID);
                $event->sheet->getDelegate()->getStyle('A29:B30')->getFill()->getStartColor()->setARGB('FFFF00');

            }
        ];
    }
}
