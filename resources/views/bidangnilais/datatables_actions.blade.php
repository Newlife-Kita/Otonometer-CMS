{!! Form::open(['route' => ['bidangnilais.destroy', $id], 'method' => 'delete', 'id' => 'table-form-'.$id]) !!}
<div class='btn-group'>
    @can('bidangnilai-edit')
        <a href="#" data-tahun="{{ $tahun }}" id="actions_{{$id}}" data-id="{{$id}}" data-idwilayah="{{ $id_wilayah }}" data-wilayah="{{ @$wilayah['nama'] }}" data-bidang="{{ @$bidang['kode'] }}. {{ @$bidang['nama'] }}" data-idbidang="{{ $id_bidang }}" data-nilai="{{ $nilai }}"  data-nilai2="{{ number_format($nilai,2,',','.') }}"  class='btn btn-outline-primary btn-xs btn-icon edit-row'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
    
    @can('bidangnilai-delete')
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-outline-danger btn-xs btn-icon btn table-del',
            'data-id' => $id,
        ]) !!}
    @endcan
</div>
{!! Form::close() !!}

