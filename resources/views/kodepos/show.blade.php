@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Kodepos
        </h1>--}}
        
        {{--@include('kodepos.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Kodepos</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('kodepos.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('kodepos.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
