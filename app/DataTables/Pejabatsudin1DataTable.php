<?php

namespace App\DataTables;

use App\Models\Pejabatsudin;
use App\Models\Sudin;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class Pejabatsudin1DataTable extends DataTable
{
    private $wilayah;

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
            return '<img class="attachment-img" style="height: 50px;" src="' . asset($data->logo) . '"></img>';
        })
            ->addColumn('action', 'pejabatsudins.datatables_actions_wilayah')
            ->rawColumns(['logo', 'action']);
    }

    public function setWilayah($id)
    {
        $this->wilayah = $id;
        return $this;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Sudin $model)
    {
        return $model->newQuery()->where('id_wilayah', $this->wilayah);
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
            'logo' => ['name' => 'logo', 'title' => 'Logo', 'searchable' => false],
            'nama_sudin' => ['name' => 'nama_sudin', 'title' => 'Suku Dinas'],
        ];
    }

    public function renders($view, $data = [], $mergeData = [], $newdata = [])
    {
        if ($this->request()->ajax() && $this->request()->wantsJson()) {
            return app()->call([$this, 'ajax']);
        }

        if ($action = $this->request()->get('action') and in_array($action, $this->actions)) {
            if ($action == 'print') {
                return app()->call([$this, 'printPreview']);
            }

            return app()->call([$this, $action]);
        }

        return view($view, $data, $mergeData)->with($this->dataTableVariable, $this->getHtmlBuilder())->with($newdata);
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'pejabatsudinsdatatable_' . time();
    }
}
