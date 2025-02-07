@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pejabat Pemerintah Daerah</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">Pejabat Pemerintah Daerah</h4>

            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                </div>

                <div class="d-md-block">
                    @can('pejabatwilayah-show')
                        <a class="btn btn-sm btn-success btn-uppercase" href="{!! route('pejabatwilayahs.download') !!}"><i
                                class="fa fa-download"></i> Download Data Pejabat Seluruh Wilayah </a>
                    @endcan
                    @can('pejabatwilayah-create')
                        <a class="btn btn-sm btn-primary btn-uppercase" href="{!! route('pejabatwilayahs.create_excel_all') !!}"><i
                                class="fa fa-upload"></i> Upload File Excel Untuk Seluruh Wilayah </a>
                    @endcan
                </div>
            </div>

            <div class="table-responsive">
                @include('pejabatwilayahs.table')
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection
