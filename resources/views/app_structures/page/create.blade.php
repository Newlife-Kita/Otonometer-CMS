@extends('layouts.app')

@section('contents')
    <div class="content">
        <div class="container">
            @include('dashforge-templates::common.errors')

            <h4 id="section1" class="mg-b-10">App Structure - Page/ Halaman/ Skema</h4>

            <p class="mg-b-30">Laman ini digunakan untuk membuat <i>instance root</i> dari <i>json structure</i> yang akan digunakan Aplikasi Otonometer. </p>

            <div style="margin-right: -15px;margin-left: -15px;">
                <div data-label="Create" class="df-example demo-forms services-forms">
                    {!! Form::open(['route' => 'app-structure.page.store', 'files' => true]) !!}
                        @include('app_structures.fields')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection
