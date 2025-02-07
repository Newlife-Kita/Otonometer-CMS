@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Log Upload
        </h1>--}}
        
        {{--@include('log_uploads.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Log Upload</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('log_uploads.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('logUploads.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
