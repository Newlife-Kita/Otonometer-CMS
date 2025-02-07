{!! Form::open(['route' => ['sektor-bidang.keuangan.destroy', $id], 'method' => 'delete', 'id' => 'table-form-'.$id]) !!}
<div class='btn-group'>
    @can('bidang-show')
        <a href="{{ route('sektor-bidang.keuangan.child_index', $id) }}" class='btn btn-outline-success btn-xs btn-icon'>
            <i class="fa fa-list"></i>
        </a>
    @endcan

    @can('bidang-edit')
        <a href="{{ route('sektor-bidang.keuangan.edit', $id) }}" class='btn btn-outline-primary btn-xs btn-icon'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
    @can('bidang-delete')
    {!! Form::button('<i class="fa fa-trash"></i>', [
        'type' => 'button',
        'class' => 'btn btn-outline-danger btn-xs btn-icon btn table-del',
        'data-id' => $id,
    ]) !!}
    @endcan
</div>
{!! Form::close() !!}
