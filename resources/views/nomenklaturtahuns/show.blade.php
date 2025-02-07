@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Nomenklaturtahun
        </h1>--}}
        
        {{--@include('nomenklaturtahuns.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Nomenklaturtahun</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('nomenklaturtahuns.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('nomenklaturtahuns.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
