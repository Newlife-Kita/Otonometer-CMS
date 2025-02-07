@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Bahasa
        </h1>--}}
        
        {{--@include('bahasas.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Bahasa</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('bahasas.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('bahasas.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
