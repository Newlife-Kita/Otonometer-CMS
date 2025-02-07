@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pejabat Suku Dinas</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">Pejabat Suku Dinas <b>{{ @$sudin->nama_sudin }}</b>
                {{ ucfirst(strtolower(@$wilayah->tipe)) }} {{ @$wilayah->nama }}</h4>

            <p class="mg-b-30">
                This is a list of your <code>Suku Dinas <b>{{ @$sudin->nama_sudin }}</b>
                    {{ ucfirst(strtolower(@$wilayah->tipe)) }} {{ @$wilayah->nama }}</code>, you can manage by clicking on
                action buttons in this table.
            </p>

            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>

                </div>

                <div class="d-md-block">
                    <a class="btn btn-sm btn-dark btn-uppercase" href="{!! route('sudins.show', @$wilayah->id) !!}"><i
                            class="fa fa-chevron-left"></i> List Sudin Of {{ ucfirst(strtolower(@$wilayah->tipe)) }}
                        {{ @$wilayah->nama }}</a>
                    @can('pejabatsudin-create')
                        <a class="btn btn-sm btn-primary btn-uppercase" href="{!! route('sudins.create_pejabat', [@$wilayah->id, @$sudin->id]) !!}"><i
                                class="fa fa-plus"></i> Add Pejabat</a>
                        <a class="btn btn-sm btn-primary btn-uppercase" href="{!! route('sudins.pejabat_template', @$sudin->id) !!}"><i
                                class="fa fa-upload"></i> Upload Data Pejabat</a>
                    @endcan
                </div>
            </div>

            <div class="table-responsive">
                {!! $dataTable->table(['width' => '100%'], true) !!}
            </div>
        </div>
    </div>
@endsection

@section('styles')
    @include('layouts.datatables_css')
@endsection

@section('scripts')
    @include('layouts.datatables_js')
    {!! $dataTable->scripts() !!}

    <script>
        $(function() {
            $(document).on('click', '.table-del', function(e) {
                let id = $(this).data('id');
                let name = $(this).data('name');
                Swal.fire({
                    text: 'Yakin hapus data?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#0168fa',
                    cancelButtonColor: '#dc3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#table-form-" + id).submit();
                    }
                })
            });
        });
    </script>
@endsection
