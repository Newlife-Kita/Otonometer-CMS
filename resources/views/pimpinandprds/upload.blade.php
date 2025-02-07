@extends('layouts.app')

@section('contents')
    <div class="content">
        <div class="container">
            @include('dashforge-templates::common.errors')

            <h4 id="section1" class="mg-b-10">Upload Data Pimpinan DPRD {!! ucfirst(@$wilayah->tipe) !!} {!! ucfirst(@$wilayah->nama) !!}</h4>

            <p class="mg-b-30">Please, fill all required fields before click save button.</p>

            <div style="margin-right: -15px;margin-left: -15px;">
                <div data-label="Create" class="df-example demo-forms services-forms">

                    {!! Form::open(['route' => ['pimpinandprds.store_excel', 'id' => @$wilayah->id], 'files' => true]) !!}
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <a href="{!! route('pimpinandprds.template', @$wilayah->id) !!}" class="btn btn-success"><i class="fa fa-download"></i>
                                Download Excel File Format</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            {!! Form::label('File', 'Upload File Excel:') !!}
                            {!! Form::file('file', [
                                'class' => 'form-control dropify',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <hr>

                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Upload', ['class' => 'btn btn-primary']) !!}
                        <a href="{!! route('pimpinandprds.index') !!}" class="btn btn-light">Cancel</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('scripts')
    <!-- Relational Form table -->
    <script>
        $(document).ready(function() {
            $(".select2").select2();
            $('.dropify').dropify({
                messages: {
                    default: 'Drag and drop file here or click',
                    replace: 'Drag and drop file here or click to Replace',
                    remove: 'Remove',
                    error: 'Sorry, the file is too large'
                }
            });
        });
    </script>
@endsection
