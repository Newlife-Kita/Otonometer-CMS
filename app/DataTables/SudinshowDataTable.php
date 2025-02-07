<?php

namespace App\DataTables;

use App\Models\Pejabatsudin;
use App\Models\Sudin;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder;


class SudinshowDataTable extends DataTable
{
    protected $wilayah;

    /**
     * @param int $loan_type
     */
    public function setWilayah(int $wilayah)
    {
        $this->wilayah = $wilayah;
        return $this;
    }

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
            return '<img class="attachment-img" style="height: 50px;" src="' . getFileUrl(@$data->logo) . '"></img>';
        })
            ->addColumn('action', 'sudins.datatables_actions_sudin')
            ->rawColumns(['logo', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Sudin $model)
    {
        return $model->newQuery()->with('wilayah')
            ->where('id_wilayah', $this->wilayah);
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
            'logo' => ['name' => 'logo', 'data' => 'logo', 'title' => 'Logo', 'searchable' => false],
            'nama_sudin' => ['name' => 'nama_sudin', 'data' => 'nama_sudin', 'title' => 'SUDIN'],
            'alamat',
            'nama_pic' => ['name' => 'nama_pic', 'data' => 'nama_pic', 'title' => 'PIC'],
            'telp_pic' => ['name' => 'telp_pic', 'data' => 'telp_pic', 'title' => 'PIC Phone'],
            'email_pic' => ['name' => 'email_pic', 'data' => 'email_pic', 'title' => 'PIC Email'],
        ];
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
}
