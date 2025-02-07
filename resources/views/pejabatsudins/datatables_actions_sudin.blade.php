{!! Form::open(['route' => ['pejabatsudins.destroy', $id], 'method' => 'delete', 'id' => 'table-form-'.$id]) !!}
<div class='btn-group'>
    @can('pejabatsudin-show')
        {{-- <a href="{{ route('pejabatsudins.show', $id) }}" class='btn btn-outline-secondary btn-xs btn-icon'>
            <i class="fa fa-eye"></i>
        </a> --}}
    @endcan
    @can('pejabatsudin-edit')
        <a href="{{ route('pejabatsudins.edit', $id) }}" class='btn btn-outline-primary btn-xs btn-icon'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
    @can('pejabatsudin-delete')
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-outline-danger btn-xs btn-icon btn table-del',
            'data-id' => $id,
        ]) !!}
    @endcan
</div>
{!! Form::close() !!}
