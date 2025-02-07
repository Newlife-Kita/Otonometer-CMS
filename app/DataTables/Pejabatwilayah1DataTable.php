<?php

namespace App\DataTables;

use App\Models\Pejabatwilayah;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class Pejabatwilayah1DataTable extends DataTable
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

        return $dataTable->editColumn('foto', function ($data) {
            return '<img class="attachment-img" style="height: 50px;" src="' . getFileUrl($data->foto) . '">';
        })->editColumn('tahun_akhir', function ($data) {
            return ($data->tahun_akhir > date('Y') ? 'Sekarang' : $data->tahun_akhir);
        })
            ->addColumn('action', 'pejabatwilayahs.datatables_actions_wilayah')
            ->rawColumns(['foto', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Pejabatwilayah $model)
    {
        return $model->newQuery()->with('jabatan')
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
                "drawCallback" => "function (settings) {
                    var api = this.api();

                    // Iterate through each row in the table
                    api.rows().every(function (index, element) {
                        var row = this.node(); // Current row node
                        var imageData = $(row).find('img').attr('src');

                        // Check if the image has been successfully loaded/rendered
                        var image = new Image();
                        image.src = imageData;

                        image.onload = function () {
                            // Image successfully loaded, you can do something here
                            // For example, add a class to the row
                            $(row).removeClass('error-row');

                        };

                        image.onerror = function () {
                            // Image failed to load, you can do something here
                            // For example, add a class to the row
                            $(row).addClass('error-row');
                        };
                    });

                }"
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
            'jabatan.nama' => ['name' => 'master_jabatan.nama', 'data' => 'master_jabatan.nama', 'title' => 'Jabatan'],
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
        return 'pejabatwilayahsdatatable_' . time();
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
