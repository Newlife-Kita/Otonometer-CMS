@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Dataran
        </h1>--}}
        
        {{--@include('datarans.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Dataran</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('datarans.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('datarans.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
