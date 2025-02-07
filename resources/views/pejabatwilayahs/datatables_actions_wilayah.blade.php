{!! Form::open(['route' => ['pejabatwilayahs.destroy', $id], 'method' => 'delete', 'id' => 'table-form-' . $id]) !!}
<div class='btn-group'>
    @can('pejabatwilayah-edit')
        <a href="{{ route('pejabatwilayahs.edit', $id) }}" class='btn btn-outline-primary btn-xs btn-icon'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
    @can('pejabatwilayah-delete')
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-outline-danger btn-xs btn-icon btn table-del',
            'data-id' => $id,
        ]) !!}
    @endcan
</div>
{!! Form::close() !!}
