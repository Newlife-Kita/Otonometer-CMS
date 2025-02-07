{!! Form::open(['route' => ['pejabatwilayahs.destroy', $id], 'method' => 'delete', 'id' => 'table-form-'.$id]) !!}
<div class='btn-group'>
    @can('pejabatwilayah-show')
        <a href="{{ route('pejabatwilayahs.show', $id) }}" class='btn btn-outline-primary btn-xs btn-icon'>
            <i class="fa fa-users"></i>
        </a>
    @endcan
</div>
{!! Form::close() !!}
