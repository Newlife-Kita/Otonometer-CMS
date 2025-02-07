<div class='btn-group'>
    @can('datawilayah-edit')
        <a href="#" data-tahun='{{$tahun}}' data-pdrb='{{$id_sektor}}' data-nilai='{{$nilai_sektor}}' data-ketinggian='{{$ketinggian}}' data-luas='{{$luas_wilayah}}' data-penduduk='{{$jumlah_penduduk}}' data-id='{{$id}}' class='btn btn-outline-primary btn-xs btn-icon btn-edit-row'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
</div>