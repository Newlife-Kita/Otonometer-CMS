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

        <div class="form-group col-sm-10">
            {!! Form::label('id_wilayah', 'Wilayah/ Daerah:',['class' => 'd-block']) !!}
            {!! Form::select('id_wilayah', !empty(@$wilayah) ? ['' => 'Pilih Wilayah', $wilayah->id => $wilayah->nama ] : ['' => 'Pilih Wilayah'] , null, ['class' => 'form-control select2','id' => 'id_wilayah', 'required']) !!}
        </div>

        <!-- Password Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('password', 'Password:', ['class' => 'd-block']) !!}
            {!! Form::password('password', ['class' => 'form-control']) !!}
        </div>

    </div>
    
    <div class="col-sm-4"></div>
</div>

<div class="clearfix"></div>
<hr>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('users.index') !!}" class="btn btn-light">Cancel</a>
</div>


@section('styles')
    <link rel="stylesheet" href="{{ asset('vendor/dashforge/lib/select2/css/select2.min.css') }}">
    <style>
        .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px);
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 5px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 34px;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('vendor/dashforge/lib/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function () {        
            $('#id_wilayah').select2({
                placeholder: "Pilih Wilayah",
                minimumInputLength: 1,
                templateResult: function(repo) {
                    if (repo.loading) {
                        return repo.text;
                    }
                    var $container = $("<div class='select2-result-repository clearfix'>" +
                        "<div class='select2-result-repository__meta'>" +
                            "<div class='select2-result-repository__title'>"+ repo.nama +"</div>" +
                            "<div class='select2-result-repository__statistics'>" +
                                "<div class='select2-result-repository__forks'>"+repo.tipe+"</div>" +
                            "</div>" +
                        "</div>" +
                    "</div>");
                    return $container;
                },
                templateSelection: function(repo) {
                    return repo.nama || repo.text;
                },
                ajax: {
                    url: '{{ route("admin.show", 1) }}',
                    beforeSend: function(){
                    },
                    data: function (params) {
                        return {
                            term: params.term,
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.items
                        };
                    },
                    cache: true
                }
            });
        });
    </script>
@endsection
