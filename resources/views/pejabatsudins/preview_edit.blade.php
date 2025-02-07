@extends('layouts.app')

@section('contents')
    <div class="content">
        <div class="container">
            @include('dashforge-templates::common.errors')

            <h4 id="section1" class="mg-b-10">Pejabat Suku Dinas</h4>

            <p class="mg-b-30">Please, fill all required fields before click save button.</p>

            <div style="margin-right: -15px;margin-left: -15px;">
                <div data-label="Edit" class="df-example demo-forms services-forms">
                    {!! Form::model($pejabatsudin, [
                        'route' => ['pejabatsudins.preview_update', $pejabatsudin->id],
                        'method' => 'patch',
                        'files' => true,
                    ]) !!}
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{!! route('pejabatsudins.preview', @$sukudinas->id) !!}" class="btn btn-light">Cancel</a>
                    </div>
                    @include('pejabatsudins.fields')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection
