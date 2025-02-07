@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            App Structure
        </h1>--}}
        
        {{--@include('app_structures.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">App Structure</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('app_structures.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('appStructures.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
