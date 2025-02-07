@extends('layouts.app')

@section('styles')
    @include('layouts.datatables_css')
@endsection

@section('contents')
    <div class="content">
        <div class="container">
            @include('flash::message')
            <h4 id="section1" class="mg-b-30">Data {{ $halaman }} {{ @$wilayah->nama }}</h4>
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>

                </div>

                <div class="d-md-block">
                    
                </div>
            </div>

            <div class="table-responsive">
                {!! $dataTable->table(['width' => '100%'], true) !!}
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('scripts')
    @include('layouts.datatables_js')
    {!! $dataTable->scripts() !!}
@endsection
