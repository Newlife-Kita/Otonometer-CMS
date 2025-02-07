{!! Form::open(['route' => ['permissions.destroy', $id], 'method' => 'delete', 'id' => 'table-form-'.$id]) !!}
<div class='btn-group'>
    <a href="{{ route('permissions.show', $id) }}" class='btn btn-outline-secondary btn-xs btn-icon'>
        <i class="fa fa-eye"></i>
    </a>
    <a href="{{ route('permissions.edit', $id) }}" class='btn btn-outline-primary btn-xs btn-icon'>
        <i class="fa fa-edit"></i>
    </a>
    {!! Form::button('<i class="fa fa-trash"></i>', [
        'type' => 'button',
        'class' => 'btn btn-outline-danger btn-xs btn-icon btn table-del',
        'data-id' => $id,
    ]) !!}
</div>
{!! Form::close() !!}
