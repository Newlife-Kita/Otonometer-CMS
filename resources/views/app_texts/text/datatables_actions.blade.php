
<div class='btn-group'>
    @can('appText-show')
        <a href="{{ route('app-text.text.edit', $id) }}" class='btn btn-outline-primary btn-xs btn-icon'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
</div>
