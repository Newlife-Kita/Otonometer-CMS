@extends('layouts.app')

@section('contents')
    {{-- <section class="content-header">
        <h1>
            Informasi
        </h1> --}}

    {{-- @include('informasis.version') --}}
    {{-- </section> --}}
    <div class="content">
        <h4 class="mg-b-30">Informasi</h4>

        @include('flash::message')

        <div class="box box-primary">
            <div class="box-body">
                @include('informasis.show_fields')

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('informasis.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
