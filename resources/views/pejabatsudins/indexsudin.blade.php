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

            <h4 class="mg-b-10">Pejabat Suku Dinas</h4>

            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <h5>{!! @$sudin->nama_sudin !!} </h5>
                    <h6>{!! @$sudin->wilayah->nama !!} </h6>
                </div>

                <div class="d-md-block">
                    <a class="btn btn-sm btn-dark btn-uppercase" href="{!! route('pejabatsudins.index') !!}"><i
                            class="fa fa-chevron-left"></i> Data Sudin </a>

                    @can('pejabatsudin-create')
                        <a class="btn btn-sm btn-primary btn-uppercase" href="{!! route('pejabatsudins.create', @$sudin->id) !!}"><i
                                class="fa fa-plus"></i> Add New</a>
                        <a class="btn btn-sm btn-primary btn-uppercase" href="{!! route('pejabatsudins.create_excel', @$sudin->id) !!}"><i
                                class="fa fa-upload"></i> Upload File Excel</a>
                    @endcan

                </div>
            </div>

            <div class="table-responsive">
                {!! $dataTable->table(['width' => '100%'], true) !!}
            </div>
        </div>
    </div>
    <!-- /.content -->
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
