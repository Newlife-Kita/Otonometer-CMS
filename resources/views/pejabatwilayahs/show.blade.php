@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Pejabatwilayah
        </h1>--}}
        
        {{--@include('pejabatwilayahs.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Pejabatwilayah</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('pejabatwilayahs.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('pejabatwilayahs.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
