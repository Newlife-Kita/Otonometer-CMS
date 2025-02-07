@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Datawilayah
        </h1>--}}
        
        {{--@include('datawilayahs.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Datawilayah</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('datawilayahs.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('datawilayahs.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
