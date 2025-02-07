<?php

namespace App\Export;

use App\Models\Wilayah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class WilayahExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithCustomValueBinder, FromQuery, WithHeadings, WithEvents
{
    private $data;
    private $columnCount;
    private $rowCount;

    private $options;
    public function __construct($data)
    {
        $this->data = $data;
        $this->rowCount = $data['wilayah']->count();
        $this->columnCount = 9;
        $this->options = $data['dataran'];
    }

    public function query()
    {
        return Wilayah::select('id','kode','nama')->orderByRaw('COALESCE(id_parent, id), kode');
    }

    // public function collection()
    // {
    //     return $this->data['wilayah_all'];
    // }

    public function headings(): array
    {
        return [
            'Id',
            'Kode',
            'Prov/Kab/Kota',
            'Dataran',
            'Latitude',
            'Longitude',
            'tahun Berdiri',
            'tahun Pembubaran',
            'Alamat Kantor Pemerintahan',
            'Alamat Kantor Dprd',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $validation = $event->sheet->getCell("D2")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Input error');
                $validation->setError('Value is not in list.');
                $validation->setPromptTitle('Pick from list');
                $validation->setPrompt('Please pick a value from the drop-down list.');
                $validation->setFormula1(sprintf('"%s"', implode(',', $this->options)));

                for ($i = 3; $i <= $this->rowCount; $i++) {
                    $event->sheet->getCell("D{$i}")->setDataValidation(clone $validation);
                }
                // set columns to autosize
                for ($i = 1; $i <= $this->columnCount; $i++) {
                    $column = Coordinate::stringFromColumnIndex($i);
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
            }
        ];
    }
}
