{!! Form::open(['route' => ['wilayahs.destroy', $id], 'method' => 'delete', 'id' => 'table-form-' . $id]) !!}
<div class='btn-group'>
    @can('wilayah-show')
        {{-- <a href="{{ route('wilayahs.show', $id) }}" class='btn btn-outline-secondary btn-xs btn-icon'>
            <i class="fa fa-eye"></i>
        </a> --}}
    @endcan

    @can('wilayah-edit')
        <a href="{{ route('wilayahs.editcities', $id) }}" class='btn btn-outline-primary btn-xs btn-icon'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
    @can('wilayah-delete')
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-outline-danger btn-xs btn-icon btn table-del',
            'data-id' => $id,
            'data-name' => 'Yakin hapus kab/kota?',
            'data-desc' => 'Data terkait kab/kota ini otomatis tidak dapat digunakan pada aplikasi',
        ]) !!}
    @endcan
</div>
{!! Form::close() !!}
