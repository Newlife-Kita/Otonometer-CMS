<!-- Description Field -->
<div class="form-group col-sm-6 col-lg-6">
    {!! Form::label('description', 'Sumber Data:', ['class' => 'd-block']) !!}
    {!! Form::text('description', null, ['class' => 'form-control']) !!}
</div>

<div class="clearfix"></div>
<hr>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('sumberdatas.index') !!}" class="btn btn-light">Cancel</a>
</div>

@section('scripts')
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
@endsection
