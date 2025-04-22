<?php

namespace App\DataTables;

use App\Models\PejabatwilayahTemp;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class PejabatwilayahPreviewAllDataTable extends DataTable
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

        return $dataTable->editColumn('foto', function ($data) {
            return '<img class="attachment-img" style="height: 50px;" src="' . env('AWS_STORAGE_URL') . $data->foto . '"></img>';
        })->addColumn('periode', function ($data) {
            return $data->tahun_lantik . ' - ' . ($data->tahun_akhir > date('Y') ? 'Sekarang' : $data->tahun_akhir);
        })
            ->addColumn('action', 'pejabatwilayahs.datatables_actions_preview_all')
            ->rawColumns(['foto', 'action', 'periode']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PejabatwilayahTemp $model)
    {
        return $model->newQuery()->with('jabatan')->with('wilayah');
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
                'dom' => 'Bfrtip',
                'order' => [[0, 'desc']],
                'buttons' => [
                    'export',
                    'reset',
                ],
                'paging' => true,
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
                    var rows = api.rows().nodes(); // Get the array of row nodes
                    var totalImages = rows.length; // Get the total number of rows

                    // Counter to keep track of the number of resolved images
                    var resolvedImages = 0;

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
                            resolvedImages++;

                            // Check if all images have been resolved
                            if (resolvedImages === totalImages) {
                                // runAfterImagesResolved();
                            }
                        };

                        image.onerror = function () {
                            // Image failed to load, you can do something here
                            // For example, add a class to the row
                            $(row).addClass('error-row');
                            resolvedImages++;

                            // Check if all images have been resolved
                            if (resolvedImages === totalImages) {
                                // runAfterImagesResolved();
                            }
                        };
                    });

                    // function runAfterImagesResolved() {
                    //     setTimeout(function () {
                    //         if ($('.error-row').length > 0) {
                    //             $('.success-msg').hide();
                    //             $('.error-msg').show();
                    //             $('#saveBtn').prop('disabled', true);
                    //         } else {
                    //             $('.error-msg').hide();
                    //             $('.success-msg').show();
                    //             $('#saveBtn').prop('disabled', false);
                    //         }
                    //     });
                    // }
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
            'foto' => ['name' => 'foto', 'data' => 'foto', 'title' => 'Foto', 'searchable' => false, 'orderable' => false],
            'wilayah.nama' => ['name' => 'wilayah.nama', 'data' => 'wilayah.nama', 'title' => 'Nama Wilayah', 'searchable' => true, 'orderable' => true],
            'jabatan.nama' => ['name' => 'jabatan.nama', 'data' => 'jabatan.nama', 'title' => 'Jabatan'],
            'nama_lengkap',
            'periode'
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
