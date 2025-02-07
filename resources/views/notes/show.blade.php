@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Note
        </h1>--}}
        
        {{--@include('notes.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Note</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('notes.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('notes.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
