<?php

namespace App\DataTables\Panel;

use App\Models\Pejabatwilayah;
use App\Models\Sudin;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class PejabatwilayahDataTable extends DataTable
{
    private $wilayah;

    public function setWilayah($id)
    {
        $this->wilayah = $id;
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
        })->editColumn('nama_lengkap', function ($data) {
            return  "<span class='tx-bold'>".@$data->nama_lengkap."</span>
            <span class='d-block'>Periode : ". @$data->tahun_lantik ." - ".  @$data->tahun_akhir ."</span>";
        })

        ->filterColumn('nama_lengkap', function($query, $keyword) {
            $query->where('nama_lengkap','like','%'.$keyword.'%')
            ->orwhere('tahun_lantik','like','%'.$keyword.'%')
            ->orwhere('tahun_akhir','like','%'.$keyword.'%');
        })

        ->addColumn('action', 'admin_panel.wilayahs.datatables_actions_pejabat')
        ->rawColumns(['foto', 'nama_lengkap', 'nama_sudin', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Pejabatwilayah $model)
    {

        $id_wilayah = $this->wilayah;

        return $model->newQuery()->selectRaw('wilayah_jabatan.id, wilayah_jabatan.foto, wilayah_jabatan.nama_lengkap, wilayah_jabatan.tahun, wilayah_jabatan.tahun_akhir, wilayah_jabatan.tahun_lantik, wilayah_jabatan.id_jabatan')
        ->where('id_wilayah', $id_wilayah)
        ->with('masterJabatan:id,nama');
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
    protected function getColumns()
    {
        return [
            'foto' => ['name' => 'foto','searchable' => false,'title' => 'Foto'],
            'nama_lengkap' => ['name' => 'nama_lengkap','title' => 'Data Pejabat'],
            'master_jabatan.nama' => ['name' => 'master_jabatan.nama', 'data' => 'master_jabatan.nama', 'title' => 'Jabatan'],
            'tahun' => ['name' => 'tahun', 'data' => 'tahun', 'title' => 'Tahun Menjabat'],
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
        return 'kodeposdatatable_' . time();
    }
}
