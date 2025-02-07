@extends('layouts.app')

@section('contents')
    {{--<section class="content-header">
        <h1>
            Member
        </h1>--}}
        
        {{--@include('members.version')--}}
    {{--</section>--}}
    <div class="content">
        <h4 class="mg-b-30">Member</h4>

        <div class="box box-primary">
            <div class="box-body">
                @include('members.show_fields')                

                <div class="clearfix"></div>
                <hr>

                <a href="{!! route('members.index') !!}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
@endsection
