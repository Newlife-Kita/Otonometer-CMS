<div class='btn-group'>
    @can('dprd-show')
        <a href="{{ route('pimpinandprds.show', $id) }}" class='btn btn-outline-primary btn-xs btn-icon'>
            <i class="fa fa-list"></i>
        </a>
    @endcan
</div>