<?php

namespace App\DataTables;

use App\Models\AppStructure;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class NodeTextDataTable extends DataTable
{
    private $parentId;

    public function setParentId(int $id)
    {
        $this->parentId = $id;
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

        return $dataTable->editColumn('image', function ($data) {
            return '<img class="attachment-img" style="height: 50px;" src="' . getFileUrl(@$data->image) . '"></img>';
        })->addColumn('action', function ($data) {
            return $data->type == "text" ? view('app_texts.text.datatables_actions')->with(['id' => $data->id])->render() : view('app_texts.node.datatables_actions')->with(['id' => $data->id])->render();
        })->rawColumns(['image', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(AppStructure $model)
    {
        return $model->newQuery()->where('parent_node_id', $this->parentId);
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
            'name',
            'property_name',
            'type',
            'description',
            'image',
            'status'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'app_textsdatatable_' . time();
    }
}
