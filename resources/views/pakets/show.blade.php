@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Paket
        </h1>--}}
        
        {{--@include('pakets.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Paket</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('pakets.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('pakets.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
