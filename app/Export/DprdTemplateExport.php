<?php

namespace App\Export;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class DprdTemplateExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithCustomValueBinder, FromView, WithEvents
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
        $this->columnCounts = 9;
    }

    public function view(): View
    {
        return view('dprds.excel');
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $validationJabatan = $event->sheet->getCell('E2')->getDataValidation();
                $validationJabatan->setType(DataValidation::TYPE_LIST);
                $validationJabatan->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validationJabatan->setAllowBlank(false);
                $validationJabatan->setShowInputMessage(true);
                $validationJabatan->setShowErrorMessage(true);
                $validationJabatan->setShowDropDown(true);
                $validationJabatan->setErrorTitle('Input error');
                $validationJabatan->setError('Value is not in list.');
                $validationJabatan->setPromptTitle('Pick from list');
                $validationJabatan->setPrompt('Please pick a value from the drop-down list.');
                $validationJabatan->setFormula1(sprintf('"%s"', implode(',', $this->jabatan)));

                $validationKomisi = $event->sheet->getCell('F2')->getDataValidation();
                $validationKomisi->setType(DataValidation::TYPE_LIST);
                $validationKomisi->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validationKomisi->setAllowBlank(false);
                $validationKomisi->setShowInputMessage(true);
                $validationKomisi->setShowErrorMessage(true);
                $validationKomisi->setShowDropDown(true);
                $validationKomisi->setErrorTitle('Input error');
                $validationKomisi->setError('Value is not in list');
                $validationKomisi->setPromptTitle('Pick from list');
                $validationKomisi->setPrompt('Please pick a value from the drop-down list.');
                $validationKomisi->setFormula1(sprintf('"%s"', implode(',', $this->komisi)));

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


                $validationJabatan->setSqref('E2:E1001');
                $validationKomisi->setSqref('F2:F1001');
                $validationPartai->setSqref('G2:G1001');

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
