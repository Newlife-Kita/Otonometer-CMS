@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Partai
        </h1>--}}
        
        {{--@include('partais.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Partai</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('partais.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('partais.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
