@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Ekonomi
        </h1>--}}
        
        {{--@include('ekonomis.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Ekonomi</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('ekonomis.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('ekonomis.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
