<div class='btn-group'>
    @can('bidangnilai-edit')
        <a href="{{ route('panel.data_keuangan.edit', $tahun) }}" class='btn btn-outline-primary btn-xs btn-icon'>
            <i class="fa fa-file-excel"></i>
        </a>
    @endcan
</div>