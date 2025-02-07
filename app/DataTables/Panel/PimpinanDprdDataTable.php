<?php

namespace App\DataTables\Panel;

use App\Models\Dprd;
use App\Models\Wilayah;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class PimpinanDprdDataTable extends DataTable
{
    private $wilayah;
    private $jabatan;

    public function setWilayah($id)
    {
        $master_wilayah = Wilayah::find($id);
        $this->wilayah = $master_wilayah;
        return $this;
    }
    
    public function setJabatan($arr_jabatan){
        $this->jabatan = array_keys($arr_jabatan);
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
    public function query(Dprd $model)
    {

        $id_wilayah = $this->wilayah;

        return $model->newQuery()->selectRaw('dprd.id, dprd.foto, dprd.nama_lengkap, dprd.tahun, dprd.tahun_akhir, dprd.tahun_lantik, dprd.id_jabatan, dprd.id_komisi, dprd.id_partai')
        ->where('dprd.id_wilayah', $id_wilayah)
        ->whereIn('dprd.id_jabatan', $this->jabatan)
        ->with('masterJabatan:id,nama')
        ->with('masterPartai:id,nama')
        ->with('masterKomisi:id,nama'); 
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
            'nama_lengkap' => ['name' => 'nama_lengkap','title' => 'Nama Lengkap'],
            'master_jabatan.nama' => ['name' => 'master_jabatan.nama', 'data' => 'master_jabatan.nama', 'title' => 'Jabatan'],
            'master_partai.nama' => ['name' => 'master_partai.nama', 'data' => 'master_partai.nama', 'title' => 'Partai'],
            'master_komisi.nama' => ['name' => 'master_komisi.nama', 'data' => 'master_komisi.nama', 'title' => 'Komisi'],
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
