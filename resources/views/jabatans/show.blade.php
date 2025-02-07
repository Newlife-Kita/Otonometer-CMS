@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Jabatan
        </h1>--}}
        
        {{--@include('jabatans.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Jabatan</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('jabatans.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('jabatans.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
