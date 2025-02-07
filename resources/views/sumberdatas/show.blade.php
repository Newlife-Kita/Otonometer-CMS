@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Sumberdata
        </h1>--}}
        
        {{--@include('sumberdatas.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Sumberdata</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('sumberdatas.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('sumberdatas.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
