<!-- Id Wilayah Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_wilayah', 'Provinsi/Kab/Kota:') !!}
    {!! Form::select('id_wilayah', [],null, ['class' => 'form-control select2', 'id' => 'id_wilayah']) !!}
</div>

<!-- Nama Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nama', 'Kecamatan/ Kelurahan:', ['class' => 'd-block']) !!}
    {!! Form::text('nama', null, ['class' => 'form-control']) !!}
</div>

<!-- Kode Field -->
<div class="form-group col-sm-2">
    {!! Form::label('kodepos', 'Kodepos:', ['class' => 'd-block']) !!}
    {!! Form::text('kodepos', null, ['class' => 'form-control', 'maxlength' => 5]) !!}
</div>


<div class="clearfix"></div>
<hr>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('kodepos.index') !!}" class="btn btn-light">Cancel</a>
</div>


@section('scripts')
    <script>
        $(document).ready(function() {
            $('#id_wilayah').select2({
                placeholder: "Select Provinsi/Kab/Kota",
                minimumInputLength: 1,
                templateResult: function(repo) {
                    if (repo.loading) {
                        return repo.text;
                    }
                    var $container = $("<div class='select2-result-repository clearfix'>" +
                        "<div class='select2-result-repository__meta'>" +
                            "<div class='select2-result-repository__title'>"+ repo.nama +"</div>" +
                            "<div class='select2-result-repository__statistics'>" +
                                "<div class='select2-result-repository__forks tx-bold'>"+repo.propinsi+"</div>" +
                            "</div>" +
                        "</div>" +
                    "</div>");
                    return $container;
                },
                templateSelection: function(repo) {
                    // console.log(repo);
                    return repo.nama || repo.text;
                },
                ajax: {
                    url: '{{ route("wilayahs.ajax") }}',
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
            }).on('select2:select', function (e) {
                var data = e.params.data;
            });
        });
    </script>

@endsection