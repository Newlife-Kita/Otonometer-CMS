@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Satuan
        </h1>--}}
        
        {{--@include('satuans.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Satuan</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('satuans.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('satuans.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
