@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Parpol
        </h1>--}}
        
        {{--@include('parpols.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Parpol</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('parpols.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('parpols.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
