@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Bidangs</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">Bidangs</h4>

            <p class="mg-b-30">
                This is a list of your <code>Bidangs</code>, you can manage by clicking on action buttons in this table.
            </p>

            <div class="table-responsive">
                @include('bidangs.table')
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection

