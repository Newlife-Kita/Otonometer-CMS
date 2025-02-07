{!! Form::open(['route' => ['app-structure.node.delete', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    @can('appStructure-edit')
        <a href="{{ route('app-structure.node.edit', $id) }}" class='btn btn-outline-primary btn-xs btn-icon'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
    @can('appStructure-delete')
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'class' => 'btn btn-outline-danger btn-xs btn-icon',
            'onclick' => "return confirm('Are you sure?')"
        ]) !!}
    @endcan
</div>
{!! Form::close() !!}
