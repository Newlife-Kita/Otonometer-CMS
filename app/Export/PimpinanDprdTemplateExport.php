<?php

namespace App\Export;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class PimpinanDprdTemplateExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithCustomValueBinder, FromView, WithEvents
{
    private $jabatan;
    private $komisi;
    private $partai;
    private $wilayah;
    private $columnCounts;

    public function __construct($jabatan, $komisi, $partai, $wilayah)
    {
        $this->jabatan = $jabatan;
        $this->komisi = $komisi;
        $this->partai = $partai;
        $this->wilayah = $wilayah;
        $this->columnCounts = 11;
    }

    public function view(): View
    {
        return view('pimpinandprds.excel');
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $validationPartai = $event->sheet->getCell('G2')->getDataValidation();
                $validationPartai->setType(DataValidation::TYPE_LIST);
                $validationPartai->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validationPartai->setAllowBlank(false);
                $validationPartai->setShowInputMessage(true);
                $validationPartai->setShowErrorMessage(true);
                $validationPartai->setShowDropDown(true);
                $validationPartai->setErrorTitle('Input error');
                $validationPartai->setError('Value is not in list');
                $validationPartai->setPromptTitle('Pick from list');
                $validationPartai->setPrompt('Please pick a value from the drop-down list.');
                $validationPartai->setFormula1(sprintf('"%s"', implode(',', $this->partai)));

                $validationPartai2 = $event->sheet->getCell('K2')->getDataValidation();
                $validationPartai2->setType(DataValidation::TYPE_LIST);
                $validationPartai2->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validationPartai2->setAllowBlank(false);
                $validationPartai2->setShowInputMessage(true);
                $validationPartai2->setShowErrorMessage(true);
                $validationPartai2->setShowDropDown(true);
                $validationPartai2->setErrorTitle('Input error');
                $validationPartai2->setError('Value is not in list');
                $validationPartai2->setPromptTitle('Pick from list');
                $validationPartai2->setPrompt('Please pick a value from the drop-down list.');
                $validationPartai2->setFormula1(sprintf('"%s"', implode(',', $this->partai)));

                $validationPartai->setSqref('G2:G1001');
                $validationPartai2->setSqref('K2:K1001');

                for ($i = 2; $i <= 1001; $i++) {
                    $event->sheet->setCellValue("A{$i}", $this->wilayah->kode);
                    $event->sheet->setCellValue("B{$i}", $this->wilayah->nama);
                }

                for ($i = 1; $i <= $this->columnCounts; $i++) {
                    $column = Coordinate::stringFromColumnIndex($i);
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
            }
        ];
    }
}
