<!-- Kode Field -->
<div class="form-group col-sm-4">
    {!! Form::label('kode', 'Kode:', ['class' => 'd-block']) !!}
    {!! Form::text('kode', null, ['class' => 'form-control', 'id' => 'kode', 'disabled']) !!}
    <span class="tx-10 alert-box"></span>
</div>

<!-- Nama Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nama', 'Nama:', ['class' => 'd-block']) !!}
    @foreach($bahasa as $lg)
        @if($loop->index > 0)
            <div class="mg-t-10"></div>
        @endif
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">{{ $lg->label }} ({{ $lg->code}})</span>
            </div>
            {!! Form::text('nama['.$lg->code.']', (!empty($kategori->nama) ? $kategori->getTranslation('nama', $lg->code) : null), ['class' => 'form-control', 'required']) !!}
        </div> 
    @endforeach
</div>


<!-- Icon Field -->
<div class="form-group col-sm-4 col-lg-4">
    {!! Form::label('icon', 'Icon:', ['class' => 'd-block']) !!}
    {!! Form::file('icon', ['class' => 'form-control dropify', 'data-default-file' => @$kategori->icon ? asset(@$kategori->icon) : '' , 'data-max-file-size' => '2M']) !!}
</div>


<div class="clearfix"></div>
<hr>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('kategoris.index') !!}" class="btn btn-light">Cancel</a>
</div>

@section('scripts')
<!-- Relational Form table -->
<script>
$(document).ready(function() {
    $('.dropify').dropify({
        messages: {
            default: 'Drag and drop file here or click',
            replace: 'Drag and drop file here or click to Replace',
            remove:  'Remove',
            error:   'Sorry, the file is too large'
        }
    });
});
</script>
<!-- End Relational Form table -->
@endsection
