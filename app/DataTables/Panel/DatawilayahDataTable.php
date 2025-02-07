<?php

namespace App\DataTables\Panel;

use App\Models\Datawilayah;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class DatawilayahDataTable extends DataTable
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
        return $dataTable->editColumn('nilai_sektor', function ($data) {
            return $data->nilai_sektor.' Ribu/Kapita';
        })->editColumn('ketinggian', function ($data) {
            if(intval(@$data->ketinggian) > 0) return number_format($data->ketinggian,2,',','.') .' mdpl';
            else return '-';
        })
        ->editColumn('luas_wilayah', function ($data) {
            if(intval(@$data->luas_wilayah) > 0) return number_format($data->luas_wilayah,2,',','.') .' Km<sup>2</sup>';
            else return '0 Km<sup>2</sup>';
        })
        ->editColumn('jumlah_penduduk', function ($data) {
            return number_format($data->jumlah_penduduk,0,',','.') .' Jiwa';
        })
        ->addColumn('action', 'admin_panel.wilayahs.pdrb_actions')
        ->rawColumns(['ketinggian','luas_wilayah','jumlah_penduduk','action']);
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
    public function query(Datawilayah $model)
    {
        return $model->newQuery()->where('id_wilayah', $this->wilayah)->with('sektor:id,nama');
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
            'tahun' => ['title' => 'Tahun'],
            'sektor.nama' => ['name' => 'sektor.nama', 'data' => 'sektor.nama', 'title' => 'Sektor PDRB'],
            'nilai_sektor' => ['title' => 'Nilai PDRB'],
            'ketinggian' => ['title' => 'Ketinggian'],
            'luas_wilayah' => ['title' => 'Luas Wilayah'],
            'jumlah_penduduk' => ['title' => 'Jumlah Penduduk'],
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
        return 'wilayahsdatatable_' . time();
    }
}
