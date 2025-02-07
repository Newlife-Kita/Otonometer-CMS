<?php

namespace App\Export;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class PejabatwilayahAllTemplateExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithCustomValueBinder, FromView, WithEvents
{
    private $wilayah;

    private $columnCount;

    public function __construct($wilayah)
    {
        $this->columnCount = 10;

        $this->wilayah = $wilayah;
    }

    public function view(): View
    {
        return view('pejabatwilayahs.excel_all')->with('wilayah', $this->wilayah);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                for ($i = 1; $i <= $this->columnCount; $i++) {
                    $column = Coordinate::stringFromColumnIndex($i);
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
            }
        ];
    }
}
