@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Homepage Pict
        </h1>--}}
        
        {{--@include('homepage_picts.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Homepage Pict</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('homepage_picts.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('homepagePicts.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
