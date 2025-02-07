<?php

namespace App\DataTables;

use App\Models\Wilayah;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class DatawilayahDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable->editColumn('logo', function ($data) {
            if (!empty($data->logo)) return '<img class="attachment-img" style="height: 50px;" src="' . asset($data->logo) . '"></img>';
            else return '-';
        })
            ->editColumn('tipe', function ($data) {
                return ucfirst(strtolower($data->tipe));
            })
            ->editColumn('nama', function ($data) {
                if ($data->tipe == 'propinsi') return '<b>' . strtoupper($data->nama) . '</b>';
                return '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . ucfirst(strtolower($data->nama));
            })->addColumn('action', 'datawilayahs.datatables_actions')
            ->rawColumns(['logo', 'nama', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Wilayah $model)
    {
        return $model->newQuery();
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
                    'reset',
                    'reload'
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
    protected function getColumns()
    {
        return [
            'logo' => ['name' => 'logo', 'data' => 'logo', 'title' => 'Logo', 'searchable' => false],
            'tipe',
            'nama',
            'kodepos',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'datawilayahsdatatable_' . time();
    }
}
