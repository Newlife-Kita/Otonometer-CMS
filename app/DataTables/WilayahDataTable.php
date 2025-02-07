<?php

namespace App\DataTables;

use App\Models\Wilayah;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class WilayahDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query) // digunakan untuk memberikan customisasi tambahan pada kolom
    {
        $dataTable = new EloquentDataTable($query);
        // ->editColumn('logo', function ($data) {
        //     return '<img class="attachment-img" style="height: 50px;" src="' . asset($data->logo) . '"></img>';
        // })
        return $dataTable->editColumn('peta_light_mode', function ($data) {
            return '<img class="attachment-img" style="height: 50px;" src="' . getFileUrl(@$data->peta_light_mode) . '"></img>';
        })->editColumn('peta_dark_mode', function ($data) {
            return '<img class="attachment-img" style="height: 50px;" src="' . getFileUrl(@$data->peta_dark_mode) . '"></img>';
        })->editColumn('nama', function ($data) {
            return $data->getTranslation('nama', app()->getLocale());
        })
            ->addColumn('action', 'wilayahs.datatables_actions')
            ->rawColumns(['peta_light_mode', 'action', 'peta_dark_mode']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Wilayah $model) // spesifikasi model yang akan digunakan
    {
        return $model->newQuery()->whereNull('id_parent')->with('masterDataran:id,nama');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '80px'])
            ->parameters([
                'dom'     => 'Bfrtip',
                'order'   => [[0, 'desc']],
                'buttons' => [
                    'export',
                    'reset',
                ],
                'initComplete' => "function() {
                    this.api().columns().every(function() {
                        var column = this;
                        var input = document.createElement(\"input\");
                        if($(column.header()).attr('title') !== 'Action'){
                            $(input).appendTo($(column.header()))
                            .on('keyup change', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        }
                    });
                }",
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns() // menentukan kolom apa saja yang ada pada tabel
    {
        return [
            'peta_light_mode' => ['searchable' => false],
            'peta_dark_mode' => ['searchable' => false],
            'kode',
            'nama',
            // 'alamat_kantor',
            // 'kodepos',
            // 'longitude',
            // 'latitude',
            'master_dataran.nama' => ['name' => 'master_dataran.nama', 'data' => 'master_dataran.nama', 'title' => 'Dataran'],
            'tahun_pendirian' => ['visible' => false],
            'tahun_pembubaran'  => ['visible' => false]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'wilayahsdatatable_' . time();
    }
}
