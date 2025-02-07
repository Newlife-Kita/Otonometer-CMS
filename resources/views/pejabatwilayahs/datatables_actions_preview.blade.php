{!! Form::open([
    'route' => ['pejabatwilayahs.preview_delete', $id],
    'method' => 'delete',
    'id' => 'table-form-' . $id,
]) !!}
<div class='btn-group'>
    @can('pejabatwilayah-edit')
        <a href="{{ route('pejabatwilayahs.edit_preview', $id) }}" class='btn btn-outline-primary btn-xs btn-icon'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
    @can('pejabatwilayah-edit')
        {!! Form::button('<i class="fa fa-image"></i>', [
            'type' => 'button',
            'class' => 'btn btn-outline-success btn-xs btn-icon btn img-upload',
            'data-id' => $id,
        ]) !!}
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
