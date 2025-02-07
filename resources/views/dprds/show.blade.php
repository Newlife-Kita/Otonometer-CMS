@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Anggota DPRD {!! strtoupper(@$wilayah->tipe) !!} - {!! @$wilayah->nama !!}</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">Anggota DPRD {!! strtoupper(@$wilayah->tipe) !!} - {!! @$wilayah->nama !!}</h4>

            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div></div>

                <div class="d-md-block">
                    <a class="btn btn-sm btn-dark btn-uppercase" href="{!! route('dprds.index') !!}"><i
                            class="fa fa-chevron-left"></i> Data Propinsi/Kab/Kota </a>

                    @can('dprd-create')
                        <a class="btn btn-sm btn-primary btn-uppercase" href="{!! route('dprds.create', @$wilayah->id) !!}"><i
                                class="fa fa-plus"></i> Add New</a>
                        <a class="btn btn-sm btn-primary btn-uppercase" href="{!! route('dprds.create_excel', @$wilayah->id) !!}"><i
                                class="fa fa-upload"></i> Upload File Excel</a>
                    @endcan
                </div>
            </div>

            <div class="table-responsive">
                @include('dprds.show_fields')
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection
