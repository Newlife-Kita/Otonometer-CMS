<?php

namespace App\DataTables;

use App\Models\Pejabatsudin;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder;


class PejabatsudinDataTable extends DataTable
{
    protected $sudin;

    /**
     * @param int $loan_type
     */
    public function setSudin(int $sudin)
    {
        $this->sudin = $sudin;
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

        return $dataTable->editColumn('foto', function ($data) {
            return '<img class="attachment-img" style="height: 50px;" src="' . getFileUrl($data->foto) . '"></img>';
        })->editColumn('tahun_akhir', function ($data) {
            return ($data->tahun_akhir > date('Y') ? 'Sekarang' : $data->tahun_akhir);
        })
            ->addColumn('action', 'sudins.datatables_actions_pejabat')
            ->rawColumns(['foto', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Pejabatsudin $model)
    {
        return $model->newQuery()->with('masterJabatan')->with('sukuDinas')
            ->where('id_suku_dinas', $this->sudin)
            ->where('tahun', '<=', date('Y'));
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
            'tahun',
            'foto' => ['name' => 'foto', 'data' => 'foto', 'title' => 'Foto', 'searchable' => false],
            'nama_lengkap',
            'master_jabatan.nama' => ['name' => 'master_jabatan.nama', 'data' => 'master_jabatan.nama', 'title' => 'Jabatan'],
            'tahun_lantik' => ['name' => 'tahun_lantik', 'data' => 'tahun_lantik', 'title' => 'Periode Mulai'],
            'tahun_akhir' => ['name' => 'tahun_akhir', 'data' => 'tahun_akhir', 'title' => 'Periode Akhir'],
            // 'id_wilayah' => ['name' => 'tahun_akhir', 'data' => 'suku_dinas.id_wilayah']
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
