<?php

namespace App\DataTables;

use App\Models\Dprd;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class Dprd1DataTable extends DataTable
{
    protected $wilayah;

    /**
     * @param int
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

        return $dataTable->editColumn('foto', function ($data) {
            return '<img class="attachment-img" style="height: 50px;" src="' . getFileUrl(@$data->foto) . '"></img>';
        })->editColumn('tahun_akhir', function ($data) {
            return ($data->tahun_akhir > date('Y') ? 'Sekarang' : $data->tahun_akhir);
        })
            ->addColumn('action', 'dprds.datatables_actions_dprd')
            ->rawColumns(['foto', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Dprd $model)
    {
        return $model->newQuery()->with('masterKomisi')->with('masterPartai')->with('masterJabatan')
            ->where('tahun', '<=', date('Y'))
            ->where('id_wilayah', $this->wilayah)->whereHas('masterJabatan', function ($query) {
                $query->where('nama', 'like', '%Komisi%')->orWhere('nama', 'like', '%Anggota%');
            });
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
            'master_komisi.nama' => ['name' => 'master_komisi.nama', 'data' => 'master_komisi.nama', 'title' => 'Komisi'],
            'master_partai.nama' => ['name' => 'master_partai.nama', 'data' => 'master_partai.nama', 'title' => 'Partai Politik'],
            'tahun_lantik' => ['name' => 'tahun_lantik', 'data' => 'tahun_lantik', 'title' => 'Periode Mulai'],
            'tahun_akhir' => ['name' => 'tahun_akhir', 'data' => 'tahun_akhir', 'title' => 'Periode Akhir']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'dprdsdatatable_' . time();
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
