<?php

namespace App\DataTables;

use App\Models\Memberaktifitas;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class MemberaktifitasDataTable extends DataTable
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

        return $dataTable->editColumn('created_at', function($data) {
            return date('d-m-Y H:i', strtotime($data->created_at));
        });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Memberaktifitas $model)
    {
        return $model
                ->newQuery()
                ->whereHas('member')
                ->with('member')
                ->with('halaman', 'halaman.wilayahSatu', 'halaman.wilayahDua');
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
            // ->addAction(['width' => '80px'])
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
            'member.name' => ["title" => "Nama Member", "orderable" => false, "searchable" => true],
            'member.email' => ["title" => "Email Member", "orderable" => false, "searchable" => true],
            'halaman.halaman_tipe' => ["title" => "Halaman", "orderable" => false, "searchable" => true],
            'halaman.wilayah_satu.nama' => ["title" => "Wilayah Satu", "orderable" => false, "searchable" => false],
            'halaman.wilayah_dua.nama' => ["title" => "Wilayah Dua", "orderable" => false, "searchable" => false],
            'created_at' => ["title" => "Waktu", "orderable" => true, ],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename():string
    {
        return 'memberaktifitasdatatable_' . time();
    }
}