{!! Form::open(['route' => ['nomenklaturs.destroy', $id], 'method' => 'delete', 'id' => 'table-form-' . $id]) !!}
<div class='btn-group'>
    @can('nomenklatur-show')
    @endcan
    @can('nomenklatur-edit')
        <a href="{{ route('nomenklaturs.edit', $id) }}" class='btn btn-outline-primary btn-xs btn-icon'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
    @can('nomenklatur-delete')
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-outline-danger btn-xs btn-icon table-del',
            'data-id' => $id
        ]) !!}
    @endcan
</div>
{!! Form::close() !!}
