@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Version
        </h1>--}}
        
        {{--@include('versions.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Version</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('versions.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('versions.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
