<?php

namespace App\DataTables;

use App\Models\FooterColumnTwo;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class FooterColumnTwoDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {

        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query){
                return '<a href="'.route('admin.footer-column-2.edit', $query).'" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                    <form class="form-delete me-2" style="display:inline-block" action="'.route('admin.footer-column-2.destroy', $query).'" method="POST">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-delete-item"><i class="fas fa-trash"></i></button>
                    </form>';
            })
            ->addColumn('icon', function ($query){
                return '<i style="font-size:25px" class="'.$query->icon.'"></i></a>';
            })
            ->addColumn('status', function ($query) {
                $checked = $query['status'] == 1 ? 'checked' : null;
                return '<label class="custom-switch switch-status mt-2" style="cursor: pointer">
                            <form class="form-status" action="'.route('admin.footer-column-2.update', $query).'" method="post" type="submit">
                                    ' . csrf_field() . '
                                    ' . method_field('PUT') . '
                            <input type="checkbox" name="custom-switch-checkbox switch_status" class="custom-switch-input" ' . $checked . '>
                            <span class="custom-switch-indicator"></span>
                            </form>
                        </label>';
            })
            ->rawColumns(['action','icon','status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(FooterColumnTwo $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('footercolumntwo-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(0)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->width(50),
            Column::make('name')->addClass('text-center')->width(300),
            Column::make('status')->addClass('text-center')->width(100),
            Column::make('action')->addClass('text-center')->width(200),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'FooterColumnTwo_' . date('YmdHis');
    }
}
