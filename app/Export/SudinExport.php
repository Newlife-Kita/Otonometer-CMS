<?php

namespace App\Export;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SudinExport implements FromView
{
    public function __construct()
    {
    }

    public function view(): View
    {
        return view('sudins.excel_sudin');
    }
}
