<?php

namespace App\Exports;

use App\Models\Evaluation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Style;

class EvaluationsExport implements FromCollection, ShouldAutoSize, WithStyles
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
            'B1' => ['font' => ['bold' => true]],
            'B11' => ['font' => ['bold' => true]],
            'B18' => ['font' => ['bold' => true]],
        ];
    }
}
