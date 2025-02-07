@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Refrence
        </h1>--}}
        
        {{--@include('refrences.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Refrence</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('refrences.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('refrences.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
