<div class="row">
    <div class="col-sm-8">
        <!-- Name Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('name', 'Name:', ['class' => 'd-block']) !!}
            {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
        </div>

        <!-- Email Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('email', 'Email:') !!}
            {!! Form::email('email', null, ['class' => 'form-control', 'required']) !!}
        </div>

        <!-- Password Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('password', 'Password:', ['class' => 'd-block']) !!}
            {!! Form::password('password', ['class' => 'form-control']) !!}
        </div>
    </div>
    
    <div class="col-sm-4">
        <table class="table table-border">
            <thead class="thead-dark">
                <tr>
                    <th>ROLE/ ACCES AS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                <tr>
                    <td>
                        <div class="custom-control custom-checkbox">
                            {!! Form::checkbox('roles', $role->id, !empty($user->roles) ? $user->roles : null, ['class' => 'custom-control-input check-all', 'id' => 'customRadio_'. $role->id]) !!}
                            {!! Form::label('customRadio_'. $role->id, ucfirst($role->name), ['class' => 'custom-control-label']) !!}
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="clearfix"></div>
<hr>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('users.index') !!}" class="btn btn-light">Cancel</a>
</div>

@section('scripts')
<!-- Relational Form table -->
<script>
    $('.dropify').dropify({
        messages: {
            default: 'Drag and drop file here or click',
            replace: 'Drag and drop file here or click to Replace',
            remove:  'Remove',
            error:   'Sorry, the file is too large'
        }
    });
    $(document).on('click', '.check-all', function() {
        $(".check-all").prop('checked', false);
        $(this).prop('checked', true);
    });
</script>
<!-- End Relational Form table -->
@endsection
