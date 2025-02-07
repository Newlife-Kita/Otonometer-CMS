@extends('layouts.app')

@section('contents')
    <div class="content">
        <div class="container">
            @include('dashforge-templates::common.errors')

            <h4 id="section1" class="mg-b-10">Tambah Data Kodepos</h4>

            <p class="mg-b-30">Please, fill all required fields before click save button.</p>

            <div style="margin-right: -15px;margin-left: -15px;">
                <div data-label="Create" class="df-example demo-forms services-forms">
                    {!! Form::open(['route' => 'kodepos.store']) !!}
                        <!-- Id Wilayah Field -->
                        <div class="form-group col-sm-6">
                            {!! Form::label('id_wilayah', 'Kabupaten/Kota:') !!}
                            {!! Form::select('id_wilayah', [], null, ['class' => 'form-control select2', 'id' => 'id_wilayah']) !!}
                        </div>

                        <!-- Nama Field -->
                        <div class="form-group col-sm-6">
                            {!! Form::label('nama', 'Kecamatan:', ['class' => 'd-block']) !!}
                            {!! Form::text('nama', null, ['class' => 'form-control']) !!}
                        </div>

                        <!-- Kode Field -->
                        <div class="form-group col-sm-2">
                            {!! Form::label('kodepos', 'Kodepos:', ['class' => 'd-block']) !!}
                            {!! Form::text('kodepos', null, ['class' => 'form-control', 'maxlength' => 5]) !!}
                        </div>


                        <div class="clearfix"></div>
                        <hr>
                        <h5>KELURAHAN</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered wd-60p">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Kelurahan</th>
                                        <th class="wd-100">Kodepos</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody class="body-kelurahan"></tbody>
                                <tfoot>
                                    <tr><td colspan="3"><button type="button" class="btn btn-xs btn-icon btn-success add-kelurahan"><i class="fas fa-plus"></i> Kelurahan</button></td></tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                            <a href="{!! route('kodepos.index') !!}" class="btn btn-light">Cancel</a>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('scripts')
    <script>
        let row = 0;
        $(document).ready(function() {
            $('#id_wilayah').select2({
                placeholder: "Select Kabupaten/Kota",
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
            });

            $(document).on('click', '.add-kelurahan', function(){
                row++;
                $(".body-kelurahan").append(`<tr id="tr_`+row+`">
                    <td>
                        {!! Form::hidden('kel_id[]', null) !!}
                        {!! Form::text('kel_name[]', null, ['class' => 'form-control']) !!}</td>
                    <td>{!! Form::text('kel_kodepos[]', null, ['class' => 'form-control', 'maxlength' => 5]) !!}</td>
                    <td><button type="button" data-id="`+row+`" id="0" class="btn btn-xs btn-icon btn-danger del-kelurahan"><i class="fas fa-times"></i></button></td>
                </tr>`);
            });

            $(document).on('click', '.del-kelurahan', function(){
                let rowid = $(this).attr('data-id');
                let id = $(this).attr('id');
                Swal.fire({
                    text: 'Yakin hapus data kelurahan?',
                    icon: 'warning',
                    showCloseButton: false,
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: "Ya Hapus",
                    cancelButtonText: "batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire("Kelurahan berhasil di hapus", "", "success");
                        $("#tr_"+rowid).fadeOut().remove();
                    }
                });
            })
        });
    </script>
@endsection