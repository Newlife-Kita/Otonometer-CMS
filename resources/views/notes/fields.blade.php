<!-- Deskripsi Field -->
<div class="form-group col-sm-10">
    {!! Form::label('description', 'Deskripsi:', ['class' => 'd-block']) !!}
    @foreach ($bahasa as $lg)
        @if ($loop->index > 0)
            <div class="mg-t-10"></div>
        @endif
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">{{ $lg->label }} ({{ $lg->code }})</span>
            </div>
            {!! Form::text(
                'description[' . $lg->code . ']',
                !empty($note->description) ? $note->getTranslation('description', $lg->code) : null,
                ['class' => 'form-control'],
            ) !!}
        </div>
    @endforeach
</div>

<div class="clearfix"></div>
<hr>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('notes.index') !!}" class="btn btn-light">Cancel</a>
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
