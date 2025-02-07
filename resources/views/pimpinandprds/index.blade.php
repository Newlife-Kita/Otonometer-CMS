@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Anggota DPRD</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">Pimpinan DPRD</h4>

            <p class="mg-b-30">
                This is a list of your <code>Pimpinan DPRD</code>, you can manage by clicking on action buttons in this
                table.
            </p>

            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                </div>

                <div class="d-md-block">
                    @can('dprd-show')
                        <a class="btn btn-sm btn-success btn-uppercase" href="{!! route('pimpinandprds.download') !!}"><i
                                class="fa fa-download"></i> Download Data Pimpinan DPRD Seluruh Wilayah </a>
                    @endcan
                    @can('dprd-create')
                        <a class="btn btn-sm btn-primary btn-uppercase" href="{!! route('pimpinandprds.create_excel_all') !!}"><i
                                class="fa fa-upload"></i> Upload File Excel Untuk Seluruh Wilayah </a>
                    @endcan
                </div>
            </div>

            <div class="table-responsive">
                @include('pimpinandprds.table')
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection
