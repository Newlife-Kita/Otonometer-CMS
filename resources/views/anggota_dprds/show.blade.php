@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Anggota Dprd
        </h1>--}}
        
        {{--@include('anggota_dprds.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Anggota Dprd</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('anggota_dprds.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('anggotaDprds.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
