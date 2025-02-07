@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Kategori
        </h1>--}}
        
        {{--@include('kategoris.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Kategori</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('kategoris.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('kategoris.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
