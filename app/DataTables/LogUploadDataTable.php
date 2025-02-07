<?php

namespace App\DataTables;

use App\Models\LogCms;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class LogUploadDataTable extends DataTable
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

        return $dataTable->addColumn('user_role', function ($data) {
            return $data->users->roles->pluck('name')->implode(', ');
        })->editColumn('row_uploaded', function ($data) {
            return "<div class=\"text-center\">" . (string) $data->row_uploaded . "</div>";
        })->editColumn('submit_date', function ($data) {
            return is_null($data->submit_date) ? "<div class=\"text-center\">-</div>" : $data->submit_date;
        })
            ->rawColumns(['row_uploaded', 'submit_date']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(LogCms $model)
    {
        return $model->newQuery()->with('users')->whereNotNull('upload_date')->orderBy('upload_date', 'desc');
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
            'users.name' => ['title' => 'Nama Uploader'],
            'user_role',
            'users.email',
            'row_uploaded' => ['title' => 'Data Terupload'],
            'upload_date' => ['title' => 'Tanggal Upload'],
            'submit_date' => ['title' => 'Tanggal Publish/Delete'],
            'status',
            'upload_type' => ['title' => 'Tipe Upload'],
            'file_name' => ['title' => 'Nama File']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'log_uploadsdatatable_' . time();
    }
}
