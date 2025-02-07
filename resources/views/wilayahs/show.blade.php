@extends('layouts.app')

@section('contents')
    <div class="content">
        <div class="container">
            @include('dashforge-templates::common.errors')

            <h4 id="section1" class="mg-b-10">Upload Data Master Provinsi/Kab/Kota</h4>

            <p class="mg-b-30">Please, use system template for upload data by download template.</p>

            <div style="margin-right: -15px;margin-left: -15px;">
                <div data-label="Create" class="df-example demo-forms services-forms">

                    <div class="row">
                        <div class="form-group col-sm-12">
                            <a href="{!! route('wilayahs.download') !!}" class="btn btn-success"><i class="fa fa-download"></i>
                                Download Template <span class="tx-12">(Excel File Format)</span></a>
                        </div>
                    </div>

                    {!! Form::open(['route' => 'wilayahs.upload', 'files' => true]) !!}
                    
                    
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
                        <a href="{!! route('wilayahs.index') !!}" class="btn btn-light">Cancel</a>
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
        $('.select2').select2();
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
<!-- End Relational Form table -->
@endsection