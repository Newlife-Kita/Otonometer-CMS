<?php

namespace App\DataTables\Panel;

use App\Models\Pejabatsudin;
use App\Models\Sudin;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class PejabatsudinDataTable extends DataTable
{
    private $wilayah;
    private $sudin;

    public function setWilayah($id)
    {
        $this->wilayah = $id;
        return $this;
    }

    public function setSudin($id)
    {
        $this->sudin = $id;
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
            <span class='d-block'>NIP : ". (@$data->nip ? @$data->nip : '-') ."</span>
            <span class='d-block'>Email : ". (@$data->email ? @$data->email : '-') ."</span>
            <span class='d-block'>Phone : ". (@$data->contact ? @$data->contact : '-')."</span>";
        })

        ->filterColumn('nama_lengkap', function($query, $keyword) {
            $query->where('nama_lengkap','like','%'.$keyword.'%')
            ->orwhere('nip','like','%'.$keyword.'%')
            ->orwhere('email','like','%'.$keyword.'%')
            ->orwhere('contact','like','%'.$keyword.'%');
        })

        ->addColumn('action', 'admin_panel.sudins.datatables_actions_pejabat')
        ->rawColumns(['foto', 'nama_lengkap', 'nama_sudin', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Pejabatsudin $model)
    {

        $id_wilayah = $this->wilayah;

        // id_suku_dinas	nama_lengkap	id_jabatan	tahun_lantik	tahun_akhir	tahun	foto	nip	contact	email	created_at	updated_at	deleted_at	created_by	updated_by	history_updated
        return $model->newQuery()->selectRaw('suku_dinas_pejabat.id, suku_dinas_pejabat.foto, suku_dinas_pejabat.nama_lengkap, suku_dinas_pejabat.tahun, suku_dinas_pejabat.nip, suku_dinas_pejabat.email, suku_dinas_pejabat.id_jabatan, suku_dinas_pejabat.id_suku_dinas')
        ->with('sukuDinas:id,nama_sudin')
        ->with('masterJabatan:id,nama')
        ->whereExists(function ($query) use ($id_wilayah) {
            $query->select('id')
                  ->from('suku_dinas')                 
                  ->where('id_wilayah', $id_wilayah)
                  ->whereColumn('suku_dinas.id', 'suku_dinas_pejabat.id_suku_dinas');
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
            'suku_dinas.nama_sudin' => ['name' => 'nama_sudin', 'data' => 'suku_dinas.nama_sudin', 'title' => 'Suku Dinas'],
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
