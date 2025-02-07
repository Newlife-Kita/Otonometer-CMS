{!! Form::open(['route' => ['panel.kodepos.destroy', $id], 'method' => 'delete', 'id' => 'table-form-' . $id]) !!}
<div class='btn-group'>
    @can('kodepos-edit')
        <a href="{{ route('panel.kodepos.edit', $id) }}" class='btn btn-outline-primary btn-xs btn-icon'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
    @can('kodepos-delete')
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-outline-danger btn-xs btn-icon table-del',
            'data-id' => $id,
        ]) !!}
    @endcan
</div>
{!! Form::close() !!}
