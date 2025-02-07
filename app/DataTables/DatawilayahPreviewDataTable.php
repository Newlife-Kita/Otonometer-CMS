<?php

namespace App\DataTables;

use App\Models\DatawilayahTemp;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class DatawilayahPreviewDataTable extends DataTable
{
    private $tahun;
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable->addColumn('action', 'datawilayahs.datatables_actions_preview');
    }

    public function setTahun($tahun)
    {
        $this->tahun = $tahun;
        return $this;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(DatawilayahTemp $model)
    {
        return $model->newQuery()->where('tahun', $this->tahun)->with('wilayah')->with('sektor');
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
                'paging' => false,
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
                'drawCallback' => "function(settings) {
                    var api = this.api();
                    var rows = api.rows().nodes(); // Get the array of row nodes
                    var totalRow = rows.length; // Get the total number of rows

                    // Counter to keep track of the number of resolved images
                    var resolvedRow = 0;

                    // Iterate through each row in the table
                    api.rows().every(function(index, element) {
                        var row = this.node(); // Current row node

                        var rowData = this.data()

                        var newData = (({ deleted_at, ...rest }) => rest)(rowData);

                        var checkRow = hasNullProperty(newData);

                        function hasNullProperty(obj) {
                            for (var key in obj) {
                                if (obj.hasOwnProperty(key) && obj[key] === null) {
                                    return true; // Object has a property with null value
                                }
                            }
                            return false; // No property with null value found
                        }

                        if(checkRow){
                            $(row).addClass('error-row');
                            resolvedRow++;

                        } else {
                            $(row).removeClass('error-row');
                            resolvedRow++;

                        }

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
            'wilayah.nama' => ['name' => 'wilayah.nama', 'title' => 'Nama Wilayah', 'searchable' => true, 'orderable' => true],
            'sektor.nama' => ['name' => 'sektor.nama', 'title' => 'Nama Sektor', 'searchable' => true, 'orderable' => true],
            'nilai_sektor' => ['title' => 'Nilai PDRB'],
            'ketinggian',
            'luas_wilayah',
            'jumlah_penduduk',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'datawilayahsdatatable_' . time();
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
