<?php
namespace App\DataTables;

use App\Models\Bidangstatistik;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class BidangnilaistatistikDataTable extends DataTable
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

        return $dataTable->editColumn('nilai', function($data) {
            return is_null($data->nilai) ? 'NULL' : number_format($data->nilai,2,',','.');
        })->addColumn('action', 'bidangnilais.statistik.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Bidangstatistik $model)
    {
        return $model->newQuery()->select('nomenklatur_amount_statistik.id','nomenklatur_amount_statistik.kode','nomenklatur_amount_statistik.id_wilayah','nomenklatur_amount_statistik.id_bidang','nomenklatur_amount_statistik.created_at','nomenklatur_amount_statistik.nilai')->with('bidang:id,kode,nama')->with('wilayah:id,kode,nama');
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
            // ->addAction(['width' => '80px'])
            ->parameters([
                'dom'     => 'Bfrtip',
                'order'   => [[0, 'asc']],
                'pageLength' => 100,
                'buttons' => [
                    'export',
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
            'kode' => ['name' => 'kode', 'data' => 'kode','title' => 'Kode (By Upload))'],
            'bidang.kode' => ['name' => 'bidang.kode', 'data' => 'bidang.kode', 'title' => 'Kode Bidang/Sektor'],
            'bidang.nama' => ['name' => 'bidang.nama', 'data' => 'bidang.nama', 'title' => 'Nama Bidang/Sektor'],
            'wilayah.nama' => ['name' => 'wilayah.nama', 'data' => 'wilayah.nama', 'title' => 'Prov/Kab/Kota'],
            'nilai'
        ];
    }

    public function renders($view, $data = [], $mergeData = [],$newdata = [])
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

    protected function filename():string
    {
        return 'bidangnilaisdatatable_' . time();
    }
}