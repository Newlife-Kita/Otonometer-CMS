
<div class='btn-group'>
    @can('appText-show')
        <a href="{{ route('app-text.node', $id) }}" class='btn btn-outline-primary btn-xs btn-icon'>
            <i class="fa fa-list"></i>
        </a>
    @endcan
</div>
