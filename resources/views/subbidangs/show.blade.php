@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Bidang
        </h1>--}}
        
        {{--@include('bidangs.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Bidang</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('bidangs.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('bidangs.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
