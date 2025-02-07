<?php

namespace App\Export;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class PejabatwilayahTemplateExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithCustomValueBinder, FromView, WithEvents
{
    private $ketua;
    private $wilayah;
    private $columnCount;
    public function __construct($wilayah, $tipe)
    {
        $this->columnCount = 9;
        $this->ketua = $tipe == "Propinsi" ? "Gubernur" : ($tipe == "Kota" ? "Walikota" : "Bupati");
        $this->wilayah = $wilayah;
    }

    public function view(): View
    {
        return view('pejabatwilayahs.excel')->with('ketua', $this->ketua)->with('wakil', 'Wakil ' . $this->ketua);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                for ($i = 2; $i <= 201; $i++) {
                    $event->sheet->setCellValue("A{$i}", $this->wilayah->kode);
                    $event->sheet->setCellValue("B{$i}", $this->wilayah->nama);
                }

                for ($i = 1; $i <= $this->columnCount; $i++) {
                    $column = Coordinate::stringFromColumnIndex($i);
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
            }
        ];
    }
}
