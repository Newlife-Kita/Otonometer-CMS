@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Job
        </h1>--}}
        
        {{--@include('jobs.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Job</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('jobs.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('jobs.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
