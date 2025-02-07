{!! Form::open(['route' => ['ekonomis.destroy', $id], 'method' => 'delete', 'id' => 'table-form-'.$id]) !!}
<div class='btn-group'>
    @can('ekonomi-show')
        {{-- <a href="{{ route('ekonomis.show', $id) }}" class='btn btn-outline-secondary btn-xs btn-icon'>
            <i class="fa fa-eye"></i>
        </a> --}}
    @endcan
    @can('ekonomi-edit')
        <a href="{{ route('ekonomis.edit', $id) }}" class='btn btn-outline-primary btn-xs btn-icon'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
    @can('ekonomi-delete')
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-outline-danger btn-xs btn-icon btn table-del',
            'data-id' => $id,
        ]) !!}
    @endcan
</div>
{!! Form::close() !!}
