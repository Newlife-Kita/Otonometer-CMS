@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Log Uploads</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">Riwayat Upload Data</h4>

            <p class="mg-b-30">
                Disini anda dapat mengetahui riwayat upload data yang terjadi. Data bisa jadi tidak lengkap terutama jika
                data diekspor dari database lain tanpa pengupload-an
            </p>

            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>

                </div>
            </div>

            <div class="table-responsive">
                @include('log_uploads.table')
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection
