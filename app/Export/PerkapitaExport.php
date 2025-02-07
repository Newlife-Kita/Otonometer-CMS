<?php

namespace App\Export;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;

class PerkapitaExport implements FromView, ShouldAutoSize
{
    public function __construct(array $data)
    {
        $this->data = $data;
    }


    public function view(): View
    {
		return view('bidangperkapitas.excel', [
            'data' => $this->data
        ]);
    }
}
