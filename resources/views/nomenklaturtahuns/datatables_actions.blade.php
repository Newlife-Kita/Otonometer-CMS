{!! Form::open([
    'route' => ['nomenklaturtahuns.destroy', $id],
    'method' => 'delete',
    'id' => 'table-form-' . $id,
]) !!}
<div class='btn-group'>
    @can('nomenklaturtahun-show')
        {{-- <a href="{{ route('nomenklaturtahuns.show', $id) }}" class='btn btn-outline-secondary btn-xs btn-icon'>
            <i class="fa fa-eye"></i>
        </a> --}}
    @endcan
    @can('nomenklaturtahun-edit')
        <a href="{{ route('nomenklaturtahuns.edit', $id) }}" class='btn btn-outline-primary btn-xs btn-icon'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
    @can('nomenklaturtahun-delete')
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-outline-danger btn-xs btn-icon table-del',
            'data-id' => $id,
        ]) !!}
    @endcan
</div>
{!! Form::close() !!}
