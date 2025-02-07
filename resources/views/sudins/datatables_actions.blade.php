{!! Form::open(['route' => ['sudins.destroy', $id], 'method' => 'delete', 'id' => 'table-form-'.$id]) !!}
<div class='btn-group'>
    @can('sudin-show')
        <a href="{{ route('sudins.show', $id) }}" class='btn btn-outline-primary btn-xs btn-icon'>
            <i class="fa fa-list"></i>
        </a>
    @endcan
</div>
{!! Form::close() !!}
