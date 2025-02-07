<?php

namespace App\DataTables;

use App\Models\Ekonomi;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class EkonomiDataTable extends DataTable
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

        return $dataTable->editColumn('icon_light_mode', function ($data) {
            return '<img class="attachment-img" style="height: 50px;" src="' . getFileUrl(@$data->icon_light_mode) . '"></img>';
        })->editColumn('icon_dark_mode', function ($data) {
            return '<img class="attachment-img" style="height: 50px;" src="' . getFileUrl(@$data->icon_dark_mode) . '"></img>';
        })
            ->addColumn('action', 'ekonomis.datatables_actions')
            ->rawColumns(['icon_light_mode', 'action', 'icon_dark_mode']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Ekonomi $model)
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
                    'export',
                    'reset',
                    'reload',
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
            'icon_light_mode' => ['name' => 'icon_light_mode', 'title' => 'Icon Light Mode', 'searchable' => false],
            'icon_dark_mode' => ['name' => 'icon_dark_mode', 'title' => 'Icon Dark Mode', 'searchable' => false],
            'nama',
            'status'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'ekonomisdatatable_' . time();
    }
}
