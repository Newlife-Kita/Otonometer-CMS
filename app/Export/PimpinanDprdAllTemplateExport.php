<?php

namespace App\Export;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class PimpinanDprdAllTemplateExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithCustomValueBinder, FromView, WithEvents
{
    private $wilayah;
    private $partai;
    private $columnCounts;
    public function __construct($wilayah, array $partai)
    {
        $this->wilayah = $wilayah;
        $this->partai = $partai;
        $this->columnCounts = 12;
    }
    public function view(): View
    {
        return view('pimpinandprds.excel_all', ['wilayah' => $this->wilayah]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $validationPartai1 = $event->sheet->getCell('F2')->getDataValidation();
                $validationPartai1->setType(DataValidation::TYPE_LIST);
                $validationPartai1->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validationPartai1->setAllowBlank(false);
                $validationPartai1->setShowInputMessage(true);
                $validationPartai1->setShowErrorMessage(true);
                $validationPartai1->setShowDropDown(true);
                $validationPartai1->setErrorTitle('Input error');
                $validationPartai1->setError('Value is not in list');
                $validationPartai1->setPromptTitle('Pick from list');
                $validationPartai1->setPrompt('Please pick a value from the drop-down list.');
                $validationPartai1->setFormula1(sprintf('"%s"', implode(',', $this->partai)));

                $validationPartai2 = $event->sheet->getCell('J2')->getDataValidation();
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

                $validationPartai1->setSqref('F2:F5001');
                $validationPartai2->setSqref('J2:J5001');


                for ($i = 1; $i <= $this->columnCounts; $i++) {
                    $column = Coordinate::stringFromColumnIndex($i);
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
            }
        ];
    }
}
