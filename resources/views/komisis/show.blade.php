@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Komisi
        </h1>--}}
        
        {{--@include('komisis.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Komisi</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('komisis.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('komisis.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
