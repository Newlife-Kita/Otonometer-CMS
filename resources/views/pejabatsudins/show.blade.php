@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Pejabatsudin
        </h1>--}}
        
        {{--@include('pejabatsudins.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Pejabatsudin</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('pejabatsudins.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('pejabatsudins.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
