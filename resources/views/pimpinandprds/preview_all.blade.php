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

            <h4 class="mg-b-10">Preview Upload Data DPRD Seluruh Wilayah</h4>

            <p class="mg-b-30">
                This is a list of your <code>DPRD Provinsi/Kabupaten/Kota</code>, you can manage by clicking on action
                buttons in this table.
            </p>

            <div class="table-responsive">
                @include('pimpinandprds.show_fields')
            </div>
            {{--
            <p class="mt-10 error-msg text-danger" style="display: none">
                Cannot save the data. Please complete or fix the data first.
            </p>

            <p class="mt-100 success-msg text-success" style="display: none">
                Everything is alright, you can savely save the data.
            </p> --}}

            <div class="d-md-block">
                @can('pejabatwilayah-delete')
                    {!! Form::open([
                        'route' => ['pimpinandprds.cancel_all'],
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
                        'route' => ['pimpinandprds.submit_all'],
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
