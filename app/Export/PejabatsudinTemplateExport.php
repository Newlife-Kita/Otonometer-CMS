<?php

namespace App\Export;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class PejabatsudinTemplateExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithCustomValueBinder, FromView, WithEvents
{
    private $jabatan;
    private $columnCount;
    public function __construct($jabatan)
    {
        $this->columnCount = 7;
        $this->jabatan = $jabatan;
    }

    public function view(): View
    {
        return view('pejabatsudins.excel');
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $validationJabatan = $event->sheet->getCell('B2')->getDataValidation();
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

                $validationJabatan->setSqref('B2:B1001');

                for ($i = 1; $i <= $this->columnCount; $i++) {
                    $column = Coordinate::stringFromColumnIndex($i);
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
            }
        ];
    }
}
