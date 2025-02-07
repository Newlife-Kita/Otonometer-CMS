<div class="row">
    <div class="col-sm-6">
        <!-- Nama Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('nama', 'Nama:', ['class' => 'd-block']) !!}
            @foreach ($bahasa as $lg)
                @if ($loop->index > 0)
                    <div class="mg-t-10"></div>
                @endif
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{ $lg->label }} ({{ $lg->code }})</span>
                    </div>
                    {!! Form::text(
                        'nama[' . $lg->code . ']',
                        !empty($ekonomi->nama) ? $ekonomi->getTranslation('nama', $lg->code) : null,
                        ['class' => 'form-control', 'required'],
                    ) !!}
                </div>
            @endforeach
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
            {!! Form::label('icon_light_mode', 'Icon Light Mode:', ['class' => 'd-block']) !!}
            {!! Form::file('icon_light_mode', [
                'class' => 'form-control dropify',
                'data-default-file' => @$ekonomi->icon_light_mode ? getFileUrl(@$ekonomi->icon_light_mode) : '',
                'data-max-file-size' => '2M',
            ]) !!}
        </div>
        <div class="form-group col-sm-12 col-lg-12">
            {!! Form::label('icon_dark_mode', 'Icon Dark Mode:', ['class' => 'd-block']) !!}
            {!! Form::file('icon_dark_mode', [
                'class' => 'form-control dropify',
                'data-default-file' => @$ekonomi->icon_dark_mode ? getFileUrl(@$ekonomi->icon_dark_mode) : '',
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
    <a href="{!! route('ekonomis.index') !!}" class="btn btn-light">Cancel</a>
</div>

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
    <!-- End Relational Form table -->
@endsection
