<li class="nav-label mg-t-25">Menu</li>

<li class="{{ Request::is('dashboard*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('dashboard') !!}"><i data-feather="home"></i><span>Home</span></a>
</li>

@php
    $wilayah = wilayah_admin_access();
@endphp


<li class="nav-label mg-t-25"><h6>{{ $wilayah->nama }}</h6></li>

@canAny(['wilayah-show', 'kodepos-show', 'datawilayah-show'])
    <li class="nav-label mg-t-25 ">Informasi Daerah</li>

    @can('wilayah-show')
    <li class="{{ Request::is('panel-admin/wilayah*') ? 'active' : '' }} nav-item">
        <a class="nav-link" href="{!! route('panel.wilayahs.index') !!}"><i data-feather="map-pin"></i><span>Tentang Daerah</span></a>
    </li>        
    @endcan

    @can('datawilayah-show')
        <li class="{{ Request::is('panel-admin/data-wilayah*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('panel.datawilayahs.index') !!}"><i data-feather="map"></i><span>Info PDRB/Tahun</span></a>
        </li>
    @endcan
    
    @if($wilayah->tipe == 'kabupaten' || $wilayah->tipe == 'kota')
        @can('kodepos-show')
            <li class="{{ Request::is('panel-admin/kodepos*') ? 'active' : '' }} nav-item">
                <a class="nav-link" href="{!! route('panel.kodepos.index') !!}"><i data-feather="tag"></i><span>Kodepos</span></a>
            </li>
        @endcan
    @endif
@endcanAny

@canAny(['pejabatwilayah-show'])
    <li class="nav-label mg-t-25">Pemerintahan</li>
    @can('pejabatwilayah-show')
        <li class="{{ Request::is('panel-admin/pejabat-daerah*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('panel.pejabat_wilayahs.index') !!}"><i data-feather="users"></i><span>Pemerintahan Daerah</span></a>
        </li>
    @endcan
@endcanAny

@canAny(['sudin-show', 'pejabatsudin-show'])
    <li class="nav-label mg-t-25">Suku Dinas Pemerintahan</li>
    @can('sudin-show')
        <li class="{{ Request::is('panel-admin/sudins*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('panel.sudins.index') !!}"><i data-feather="book"></i><span>Suku Dinas</span></a>
        </li>
    @endcan
    
    @can('pejabatsudin-show')
        <li class="{{ Request::is('panel-admin/pejabat-suku-dinas*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('panel.pejabat_sudins.index') !!}"><i data-feather="users"></i><span>Pejabat Suku Dinas</span></a>
        </li>
    @endcan
@endcanAny

@canAny(['dprd-show'])
    <li class="nav-label mg-t-25">DPRD</li>
    @can('dprd-show')
        <li class="{{ Request::is('panel-admin/pimpinan-dprd*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('panel.pimpinan_dprds.index') !!}"><i data-feather="users"></i><span>Pimpinan DPRD</span></a>
        </li>
        <li class="{{ Request::is('panel-admin/anggota-dprd*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('panel.anggota_dprds.index') !!}"><i data-feather="users"></i><span>Anggota DPRD</span></a>
        </li>
    @endcan 
@endcanAny

@canAny(['bidangnilai-show'])
    <li class="nav-label mg-t-25">Nilai Sektor & Bidang</li>

    @can('bidangnilai-show')
        <li class="{{ Request::is('panel-admin/data-keuangan*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('panel.data_keuangan.index') !!}"><i data-feather="check-square"></i><span>Keuangan</span></a>
        </li>
        <li class="{{ Request::is('panel-admin/data-statistik*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('panel.data_statistik.index') !!}"><i data-feather="check-square"></i><span>Statistik</span></a>
        </li>
        <li class="{{ Request::is('panel-admin/data-ekonomi*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('panel.data_ekonomi.index') !!}"><i data-feather="check-square"></i><span>Ekonomi</span></a>
        </li>
    @endcan
@endcanAny