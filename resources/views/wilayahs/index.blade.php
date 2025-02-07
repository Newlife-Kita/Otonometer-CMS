@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Provinsi/Kabupaten/Kota</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">Provinsi/Kabupaten/Kota</h4>

            <p class="mg-b-30">
                This is a list of your <code>Province</code>, you can manage by clicking on action buttons in this table.
                Show list of all available cities by clicking <span class='mg-l-10 btn btn-outline-success btn-xs btn-icon'><i
                        class='fa fa-list'></i></span>
            </p>

            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    {{-- {!! Form::selectRange('number', 1945, date('Y'), date('Y'), [
                        'placeholder' => 'Pick Years',
                        'class' => 'form-control select2',
                        'id' => 'tahun-select',
                    ]) !!} --}}
                </div>

                <div class="d-md-block">
                    @can('wilayah-show')
                        <a class="btn btn-sm btn-success btn-uppercase" href="{!! route('wilayahs.show',1) !!}"><i class="fa fa-upload"></i> Upload Excel</a>
                    @endcan
                    @can('wilayah-create')
                        <a class="btn btn-sm btn-primary btn-uppercase" href="{!! route('wilayahs.create') !!}"><i class="fa fa-plus"></i> Provinsi</a>
                    @endcan
                </div>
            </div>

            <div class="table-responsive">
                @include('wilayahs.table')
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection
