{!! Form::open(['route' => ['sudins.destroy', $id], 'method' => 'delete', 'id' => 'table-form-'.$id]) !!}
<div class='btn-group'>
    @can('sudin-show')
        <a href="{{ route('sudins.pejabat', [$id_wilayah, $id]) }}" class='btn btn-outline-success btn-xs btn-icon'>
            <i class="fa fa-users"></i>
        </a>
    @endcan
    @can('sudin-edit')
        <a href="{{ route('sudins.edit', [$id_wilayah, $id]) }}" class='btn btn-outline-primary btn-xs btn-icon'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
    @can('sudin-delete')
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-outline-danger btn-xs btn-icon btn table-del',
            'data-id' => $id,
        ]) !!}
    @endcan
</div>
{!! Form::close() !!}
