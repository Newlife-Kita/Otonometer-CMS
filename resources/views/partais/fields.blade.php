<div class="row">
    <div class="col-sm-6">
        <!-- Nama Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('nama', 'Nama:', ['class' => 'd-block']) !!}
            {!! Form::text('nama', null, ['class' => 'form-control']) !!}
        </div>

        <!-- Status Field -->
        <div class="form-group col-sm-6">
            {!! Form::label('status', 'Status:', ['class' => 'd-block']) !!}
            {!! Form::select(
                'status',
                ['' => 'Pilih Status', 'tampil' => 'Tampilkan', 'sembunyikan' => 'Sembunyikan'],
                null,
                ['class' => 'form-control select2', 'required'],
            ) !!}
        </div>
    </div>
    <div class="col-sm-3">
        <!-- Icon Field -->
        <div class="form-group col-sm-12 col-lg-12">
            {!! Form::label('logo', 'Logo:', ['class' => 'd-block']) !!}
            {!! Form::file('logo', [
                'class' => 'form-control dropify',
                'data-default-file' => @$partai->logo ? getFileUrl(@$partai->logo) : '',
                'data-max-file-size' => '2M',
            ]) !!}
        </div>
    </div>
</div>

<div class="clearfix"></div>
<hr>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('partais.index') !!}" class="btn btn-light">Cancel</a>
</div>

@section('scripts')
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
