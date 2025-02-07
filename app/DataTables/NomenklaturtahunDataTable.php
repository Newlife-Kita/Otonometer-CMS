<?php

namespace App\DataTables;

use App\Models\Nomenklaturtahun;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class NomenklaturtahunDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'nomenklaturtahuns.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Nomenklaturtahun $model)
    {
        return $model->newQuery();
        // return $model->newQuery()->selectRaw('distinct(nomenklatur_tahun.tahun)');
        // return $model->newQuery()->select('nomenklatur_tahun.id', 'nomenklatur_tahun.id_nomenklatur', 'nomenklatur_tahun.tahun')->with('nomenklatur:id,kode,nama');
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
            'tahun' => ['title' => 'Tahun Data'],
            'active' => ['title' => 'Status', 'render' => '\'<div class="editor-active" >\' + (full[\'active\'] == \'active\' ? \'<i class="fas fa-check-circle btn-success btn btn-sm w-15"> Aktif </i>\' : \'<i class="fas fa-times-circle btn-danger btn btn-sm w-15"> Nonaktif </i>\') + \'</div>\';\'\''],
            'default_year' => ['title' => 'Tahun Default', 'render' => '\'<div class="editor-active" >\' + (full[\'default_year\'] == 1 ? \'<i class="fas fa-check-circle btn-success btn btn-sm w-15"> Default </i>\' : \'\') + \'</div>\';\'\'' ]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'nomenklaturtahunsdatatable_' . time();
    }
}
