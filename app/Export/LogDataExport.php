<?php

namespace App\Export;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class LogDataExport implements FromView, WithEvents
{
    private $log;
    public function __construct(Collection $log)
    {
        $this->log = $log;
    }

    public function view(): View
    {
        return view('app_activity.log.excel', ["log" => $this->log]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {



                for ($i = 1; $i <= 5; $i++) {
                    $column = Coordinate::stringFromColumnIndex($i);
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
            }
        ];
    }
}
