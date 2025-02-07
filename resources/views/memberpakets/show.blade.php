@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Memberpaket
        </h1>--}}
        
        {{--@include('memberpakets.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Memberpaket</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('memberpakets.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('memberpakets.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
