@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Informasi Daerah/Tahun </li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">Preview Informasi Daerah/Tahun {{ $tahun }}</h4>

            <p class="mg-b-30">
                This is a list of your <code> preview Informasi Daerah/Tahun </code>, you can manage by clicking on action
                buttons in
                this table.
            </p>

            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>

                </div>

            </div>

            <div class="table-responsive">
                @include('datawilayahs.table')
            </div>


            <div class="d-md-block">
                @can('pejabatwilayah-delete')
                    {!! Form::open([
                        'route' => ['datawilayahs.cancel', $tahun],
                        'method' => 'delete',
                        'id' => 'cancel',
                        'class' => 'form-inline',
                    ]) !!}
                    {!! Form::button('<i class="fa fa-times"></i> Cancel', [
                        'type' => 'button',
                        'class' => 'btn btn-sm btn-danger btn-uppercase',
                        'id' => 'delete',
                    ]) !!}
                    {!! Form::close() !!}
                @endcan

                @can('pejabatwilayah-create')
                    {!! Form::open([
                        'route' => ['datawilayahs.submit', $tahun],
                        'id' => 'save-data',
                        'class' => 'form-inline',
                    ]) !!}
                    {!! Form::button('Submit <i class="fa fa-paper-plane"></i>', [
                        'type' => 'button',
                        'class' => 'btn btn-sm btn-success btn-uppercase',
                        'id' => 'saveBtn',
                    ]) !!}
                    {!! Form::close() !!}
                @endcan
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection
