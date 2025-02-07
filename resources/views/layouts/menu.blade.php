<li class="nav-label mg-t-25">Menu</li>

<li class="{{ Request::is('dashboard*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('dashboard') !!}"><i data-feather="home"></i><span>Home</span></a>
</li>


@canAny(['dataran-show', 'wilayah-show', 'ekonomi-show', 'kodepos-show', 'satuan-show', 'jabatan-show', 'bahasa-show',
    'komisi-show', 'partai-show', 'job-show', 'refrence-show', 'kategori-show', 'informasi-show', 'bidang-show',
    'note-show', 'sumberdata-show'])
    <li class="nav-label mg-t-25 ">Data Master</li>

    @canAny(['dataran-show', 'wilayah-show', 'ekonomi-show', 'kodepos-show'])
        <li
            class="{{ Request::is('datarans*') || Request::is('ekonomis*') || Request::is('wilayahs*') || Request::is('kodepos*') ? 'active show' : '' }} nav-item with-sub">
            <a class="nav-link" href="#"><i data-feather="check-square"></i><span>Master Daerah</span></a>
            <ul>
                @can('dataran-show')
                    <li class="{{ Request::is('datarans*') ? 'active' : '' }}">
                        <a href="{!! route('datarans.index') !!}"><i data-feather="map"></i><span>Dataran/Infografis</span></a>
                    </li>
                @endcan
                @can('ekonomi-show')
                    <li class="{{ Request::is('ekonomis*') ? 'active' : '' }}">
                        <a href="{!! route('ekonomis.index') !!}"><i data-feather="tag"></i><span>PDRB Daerah</span></a>
                    </li>
                @endcan
                @can('wilayah-show')
                    <li class="{{ Request::is('wilayahs*') ? 'active' : '' }}">
                        <a href="{!! route('wilayahs.index') !!}"><i data-feather="map-pin"></i><span>Provinsi/Kab/Kota</span></a>
                    </li>
                @endcan

                @can('kodepos-show')
                    <li class="{{ Request::is('kodepos*') ? 'active' : '' }}">
                        <a href="{!! route('kodepos.index') !!}"><i data-feather="tag"></i><span>Kodepos</span></a>
                    </li>
                @endcan
            </ul>
        </li>
    @endcanany

    @can('satuan-show')
        <li class="{{ Request::is('satuans*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('satuans.index') !!}"><i data-feather="check-square"></i><span>Satuan</span></a>
        </li>
    @endcan

    @can('jabatan-show')
        <li class="{{ Request::is('jabatans*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('jabatans.index') !!}"><i data-feather="check-square"></i><span>Jabatan</span></a>
        </li>
    @endcan

    @canAny(['komisi-show', 'partai-show'])
        <li class="{{ Request::is('komisis*') || Request::is('partais*') ? 'active show' : '' }} nav-item with-sub">
            <a class="nav-link" href="#"><i data-feather="check-square"></i><span>Master DPRD</span></a>
            <ul>
                @can('komisi-show')
                    <li class="{{ Request::is('komisis*') ? 'active' : '' }}">
                        <a href="{!! route('komisis.index') !!}"><i data-feather="check-square"></i><span>Komisi</span></a>
                    </li>
                @endcan
                @can('partai-show')
                    <li class="{{ Request::is('partais*') ? 'active' : '' }}">
                        <a href="{!! route('partais.index') !!}"><i data-feather="check-square"></i><span>Partai Politik</span></a>
                    </li>
                @endcan
            </ul>
        </li>
    @endcanany

    @canAny(['job-show', 'refrence-show'])
        <li class="{{ Request::is('jobs*') || Request::is('refrences*') ? 'active show' : '' }} nav-item with-sub">
            <a class="nav-link" href="#"><i data-feather="check-square"></i><span>Master Regis.Member</span></a>
            <ul>
                @can('job-show')
                    <li class="{{ Request::is('jobs*') ? 'active' : '' }}">
                        <a href="{!! route('jobs.index') !!}"><i data-feather="briefcase"></i><span>Pekerjaan</span></a>
                    </li>
                @endcan

                @can('refrence-show')
                    <li class="{{ Request::is('refrences*') ? 'active' : '' }}">
                        <a href="{!! route('refrences.index') !!}"><i data-feather="book"></i><span>Sumber Informasi</span></a>
                    </li>
                @endcan
            </ul>
        </li>
    @endcanany

    @can('kategori-show')
        <li class="{{ Request::is('kategoris*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('kategoris.index') !!}"><i data-feather="check-square"></i><span>Kategori
                    Aktifitas</span></a>
        </li>
    @endcan
    @can('informasi-show')
        <li class="{{ Request::is('informasis*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('informasis.index') !!}"><i data-feather="info"></i><span>File Informasi</span></a>
        </li>
    @endcan

    @can('bidang-show')
        <li class="{{ Request::is('sektor-bidang*') || Request::is('nomenklatur*') ? 'active show' : '' }} nav-item with-sub">
            <a class="nav-link" href="#"><i data-feather="check-square"></i><span>Master Sektor/Bidang</span></a>
            <ul>
                <li class="{{ Request::is('sektor-bidang/keuangan*') ? 'active' : '' }}"><a
                        href="{{ route('sektor-bidang.keuangan.index') }}"><i
                            data-feather="dollar-sign"></i><span>Keuangan</span></a></li>
                <li class="{{ Request::is('sektor-bidang/ekonomi*') ? 'active' : '' }}"><a
                        href="{{ route('sektor-bidang.ekonomi.index') }}"><i
                            data-feather="book-open"></i><span>Ekonomi</span></a></li>
                <li class="{{ Request::is('sektor-bidang/statistik*') ? 'active' : '' }}"><a
                        href="{{ route('sektor-bidang.statistik.index') }}"><i
                            data-feather="bar-chart"></i><span>Statistik</span></a></li>
                <li class="{{ Request::is('nomenklatur*') ? 'active' : '' }}">
                    <a href="{!! route('nomenklaturs.index') !!}"><i data-feather="bookmark"></i><span>Tahun Nomenklatur</span></a>
                </li>
            </ul>
        </li>
    @endcan

    @can('note-show')
        <li class="{{ Request::is('notes*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('notes.index') !!}"><i data-feather="clipboard"></i><span>Catatan
                    Bidang/Sektor</span></a>
        </li>
    @endcan

    @can('sumberdata-show')
        <li class="{{ Request::is('sumberdatas*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('sumberdatas.index') !!}"><i data-feather="archive"></i><span>Sumber data</span></a>
        </li>
    @endcan
@endcanAny

@canAny(['version-show', 'appStructure-show', 'appText-show'])
    <li class="nav-label mg-t-25">App Management</li>
    @can('version-show')
        <li class="{{ Request::is('versions*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('versions.index') !!}"><i data-feather="tag"></i><span>Versi Aplikasi Mobile</span></a>
        </li>
        <li class="{{ Route::is('regions*') || Route::is('log.*') ? 'active show' : '' }} nav-item with-sub">
            <a class="nav-link" href="#"><i data-feather="bar-chart-2"></i><span>Aktivitas Aplikasi</span></a>
            <ul>
                <li class="{{ Route::is('regions*') ? 'active' : '' }} nav-item"><a href="{!! route('regions.index') !!}">Log
                        Wilayah</a>
                </li>
                <li class="{{ Route::is('log.*') ? 'active' : '' }} nav-item"><a href="{!! route('log.index') !!}">Log
                        Harian</a></li>
            </ul>
        </li>
    @endcan
   

    @can('appText-show')
        <li class="{{ Request::is('app-text*') || Request::is('app-structure*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('app-structure.page') !!}"><i data-feather="type"></i><span>Teks Aplikasi</span></a>
        </li>
    @endcan

    @can('appText-show')
    <li class="{{ Request::is('homepagePicts*') ? 'active' : '' }} nav-item">
        <a class="nav-link" href="{!! route('homepagePicts.index') !!}"><i data-feather="image"></i><span>Gambar Homepage</span></a>
    </li>
    @endcan

    @can('appText-show')
        <li class="{{ Request::is('bahasas*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('bahasas.index') !!}"><i data-feather="globe"></i><span>Bahasa
                    Aplikasi</span></a>
        </li>
    @endcan
@endcanAny

@can('logUpload-show')
    <li class="nav-label mg-t-25">CMS Logging</li>
    <li class="{{ Request::is('log-upload*') ? 'active' : '' }} nav-item">
        <a class="nav-link" href="{!! route('log-data.upload') !!}"><i data-feather="upload"></i><span>Data Riwayat
                Upload</span></a>
    </li>
    <li class="{{ Request::is('log-publish*') ? 'active' : '' }} nav-item">
        <a class="nav-link" href="{!! route('log-data.publish') !!}"><i data-feather="save"></i><span>Data Riwayat
                Publish</span></a>
    </li>
    <li class="{{ Request::is('log-summary*') ? 'active' : '' }} nav-item">
        <a class="nav-link" href="{!! route('log-data.summary') !!}"><i data-feather="pie-chart"></i><span>Lihat Data</a>
    </li>
@endcan


@canAny(['permissiongroup-show', 'permission-show', 'role-show', 'user-show'])
    <li class="nav-label mg-t-25">User Management</li>

    <li class="{{ Request::is('roles*') ? 'active' : '' }} nav-item">
        <a class="nav-link" href="{!! route('roles.index') !!}"><i data-feather="lock"></i><span>Roles</span></a>
    </li>

    @can('user-show')
        <li class="{{ Request::is('users*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('users.index') !!}"><i data-feather="user-plus"></i><span>User Admin</span></a>
        </li>
    @endcan

    @can('admin-show')
        <li class="{{ Request::is('admin*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('admin.index') !!}"><i data-feather="user-plus"></i><span>Admin
                    Wilayah/Daerah</span></a>
        </li>
    @endcan
@endcanAny

@canAny(['paket-show', 'member-show', 'memberpaket-show', 'memberaktifitas-show'])
    <li class="nav-label mg-t-25">Akun Member</li>

    @can('paket-show')
        <li class="{{ Request::is('pakets*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('pakets.index') !!}"><i data-feather="box"></i><span>Paket</span></a>
        </li>
    @endcan

    @can('member-show')
        <li class="{{ Request::is('members*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('members.index') !!}"><i data-feather="user-plus"></i><span>Member</span></a>
        </li>
        <li class="{{ Request::is('members/log*') ? 'active' : '' }} nav-item">
            <a href="{!! route('members.log-register') !!}" class="nav-link"><i data-feather="bar-chart-2"></i><span>Member Registration Log</span"></a>
        </li>
    @endcan

    @can('memberpaket-show')
        <li class="{{ Request::is('memberpakets*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('memberpakets.index') !!}"><i data-feather="user-check"></i><span>Paket
                    Member</span></a>
        </li>
    @endcan

    @can('memberaktifitas-show')
        <li class="{{ Request::is('memberaktifitas*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('memberaktifitas.index') !!}"><i data-feather="user-check"></i><span>Aktifitas
                    Member</span></a>
        </li>
    @endcan
@endcanAny

@canAny(['sudin-show', 'pejabatsudin-show', 'datawilayah-show', 'pejabatwilayah-show', 'dprd-show'])
    <li class="nav-label mg-t-25">Data Pemda/ Pemerintahan, <br />Suku Dinas & DPRD</li>
    @can('sudin-show')
        <li class="{{ Request::is('sudins*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('sudins.index') !!}"><i data-feather="book"></i><span>Suku Dinas</span></a>
        </li>
    @endcan

    @can('pejabatwilayah-show')
        <li class="{{ Request::is('pejabat-wilayah*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('pejabatwilayahs.index') !!}"><i data-feather="users"></i><span>Pemerintah
                    Daerah</span></a>
        </li>
    @endcan

    @can('dprd-show')
        <li
            class="{{ Request::is('pimpinan-dprd*') || Request::is('anggota-dprd*') ? 'active show' : '' }} nav-item with-sub">
            <a class="nav-link" href="#"><i data-feather="users"></i><span>DPRD</span></a>
            <ul>
                <li class="{{ Request::is('pimpinan-dprd*') ? 'active' : '' }}"><a
                        href="{{ route('pimpinandprds.index') }}"><i data-feather="award"></i><span>Pimpinan DPRD</span></a>
                </li>
                <li class="{{ Request::is('anggota-dprd*') ? 'active' : '' }}"><a href="{{ route('dprds.index') }}"><i
                            data-feather="users"></i><span>Anggota DPRD</span></a></li>
            </ul>
        </li>
    @endcan

    @can('datawilayah-show')
        <li class="{{ Request::is('datawilayahs*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('datawilayahs.index') !!}"><i data-feather="map"></i><span>Informasi
                    Daerah/Tahun</span></a>
        </li>
    @endcan
@endcanAny

@canAny(['nomenklaturtahun-show', 'bidangnilai-show'])
    <li class="nav-label mg-t-25">Nilai Sektor & Bidang</li>

    @can('nomenklaturtahun-show')
        <li class="{{ Request::is('set-tahun-data*') ? 'active' : '' }} nav-item">
            <a class="nav-link" href="{!! route('nomenklaturtahuns.index') !!}"><i data-feather="calendar"></i><span>Tahun
                    Data</span></a>
        </li>
    @endcan

    @can('bidangnilai-show')
        <li
            class="{{ Request::is('data-keuangan*') || Request::is('data-statistik*') || Request::is('data-ekonomi*') ? 'active show' : '' }} nav-item with-sub">
            <a class="nav-link" href="#"><i data-feather="check-square"></i><span>Data Nilai Sektor</span></a>
            <ul>
                <li class="{{ Request::is('data-keuangan*') ? 'active' : '' }}"><a
                        href="{{ route('data-keuangan.index') }}">Keuangan</a></li>
                <li class="{{ Request::is('data-ekonomi*') ? 'active' : '' }}"><a href="{!! route('data-ekonomi.index') !!}">Ekonomi</a>
                </li>
                <li class="{{ Request::is('data-statistik*') ? 'active' : '' }}"><a
                        href="{!! route('data-statistik.index') !!}">Statistik</a></li>
            </ul>
        </li>
    @endcan
@endcanAny
@can('jabatananggotadprd-show')
<li class="{{ Request::is('jabatananggotadprds*') ? 'active' : '' }} nav-item">
    <a class="nav-link" href="{!! route('jabatananggotadprds.index') !!}"><i data-feather="edit-3"></i><span>Jabatananggotadprds</span></a>
</li>
@endcan

