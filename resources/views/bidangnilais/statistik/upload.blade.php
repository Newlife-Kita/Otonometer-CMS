@extends('layouts.app')

@section('contents')
    <div class="content">
        <div class="container">
            @if (session()->has('success'))
                <div class="alert alert-success">
                    {!! session()->get('success') !!}
                </div>
            @else
                @include('dashforge-templates::common.errors')
            @endif


            <h4 id="section1" class="mg-b-10">Upload Nilai Sektor/Bidang Statistik</h4>

            <p class="mg-b-30">Please, fill all required fields before click save button.</p>

            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <a href="{!! route('data-statistik.index') !!}" class="btn btn-light"><i class="fas fa-chevron-left"></i> Back To List </a>
                <a href="#modalUpload" class="btn btn-dark" data-toggle="modal"><i class="fas fa-file-excel"></i> Upload
                    File Excel</a>
            </div>
            <div class="clearfix"></div>
            <hr>
            <div>
                <div class="alert alert-primary" role="alert">
                    <h5 class="alert-heading">Publish Data Upload!</h5>
                    <hr>
                    <p class="mb-0">
                    <ol>
                        <li>Data bisa di upload berulang kali/ partial (data dengan Wilayah & sektor yang sama tidak akan
                            tersimpan di database)</li>
                        <li>Data yang sudah di upload dapat di hapus/ ubah jika data salah.</li>
                        <li>Jika data sudah sesuai, data dapat di publish dengan cara memilih tahun data terlebih dahulu.
                        </li>
                        <li class="tx-16 tx-danger tx-bold">"Pastikan Data sudah sesuai sebelum di publish, karena publish
                            data hanya bisa dilakukan 1x."</li>
                    </ol>
                    </p>
                </div>
                <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                    <div>
                        <button type="button" class="btn btn-danger btn-truncate"><i class="fas fa-trash"></i>
                            &nbsp;Hapus/Reset Data Upload</button>
                    </div>

                    <div class="wd-60p d-md-block">
                        @can('bidangnilai-publish')
                            <div class="input-group mg-b-10 justify-content-end">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Tahun Data</span>
                                </div>
                                {!! Form::select('tahun', $tahun, null, [
                                    'class' => 'form-control select2',
                                    'id' => 'tahun_data',
                                    'required',
                                ]) !!}
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-success btn-publish"><i class="fas fa-save"></i>
                                        &nbsp;Publish Data Statistik</button>
                                </div>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                {!! $dataTable->table(['width' => '100%'], true) !!}
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalUpload" role="dialog" tabindex="-1" data-keyboard="false" data-backdrop="static"
        aria-labelledby="exampleModalLabel3" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content tx-14">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel3">Upload File Excel Statistik</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {!! Form::open(['route' => 'data-statistik.upload', 'id' => 'formupload', 'files' => true]) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-sm-12">
                            {!! Form::file('file', [
                                'class' => 'form-control dropify',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary tx-13 pop-upload" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary pop-upload btn-upload"><i class="fas fa-disc"></i>
                        Upload</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalNilai" role="dialog" data-keyboard="false" data-backdrop="static"
        aria-labelledby="exampleModalLabel3" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content tx-14">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel3">Ubah Data Nilai</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="labelWilayah" class="col-sm-2 col-form-label tx-bold">Prov/Kab/Kota</label>
                                    <div class="col-sm-10" id="labelWilayah"></div>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label for="labelSektor" class="col-sm-2 col-form-label tx-bold">Bidang/Sektor</label>
                                    <div class="col-sm-10" id="labelSektor"></div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th>Prov/Kab/Kota</th>
                                <th>Sektor/Bidang</th>
                                <th>Nilai</th>
                            </tr>
                            <tr>
                                <td>
                                    {!! Form::select('pop_wilayah', $wilayah, null, [
                                        'class' => 'form-control select2',
                                        'required',
                                        'id' => 'pop_wilayah',
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('pop_sektor', $sektor, null, [
                                        'class' => 'form-control select2',
                                        'required',
                                        'id' => 'pop_sektor',
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::hidden('pop_id', null, [
                                        'id' => 'pop_id',
                                    ]) !!}
                                    {!! Form::number('pop_nilai', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'pop_nilai',
                                    ]) !!}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary tx-13" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary edit-update"><i class="fas fa-disc"></i> Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    @include('layouts.datatables_css')
    <style>
        .input-group .input-group-prepend .select2-container {
            width: 100% !important;
        }

        .input-group .select2-container {
            width: 40% !important;
        }

        .select2-container {
            z-index: 9999 !important;
        }

        .input-group .select2-container {
            z-index: 99 !important;
        }
    </style>
@endsection

@section('scripts')
    @include('layouts.datatables_js')
    {!! $dataTable->scripts() !!}
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

        @if (session()->has('checksum-statistik'))

            @php
                $checksum = session('checksum-statistik');
            @endphp

            // the loader html
            var sweet_loader =
                '<div class="sweet_loader"><svg viewBox="0 0 140 140" width="140" height="140"><g class="outline"><path d="m 70 28 a 1 1 0 0 0 0 84 a 1 1 0 0 0 0 -84" stroke="rgba(0,0,0,0.1)" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"></path></g><g class="circle"><path d="m 70 28 a 1 1 0 0 0 0 84 a 1 1 0 0 0 0 -84" stroke="#71BBFF" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-dashoffset="200" stroke-dasharray="300"></path></g></svg></div>';

            $(document).ready(function() {
                Swal.fire({
                    title: "{{ $checksum['status'] ? 'Hasil upload valid' : 'Hasil Upload Tidak Valid' }}",
                    icon: "{{ $checksum['status'] ? 'success' : 'warning' }}",
                    html: "<p>Hash Excel : </p><p>{{ $checksum['excel'] }}</p><p>Hash Database : </p><p>{{ $checksum['db'] }}</p>",
                    confirmButtonText: 'Download File',
                    showCancelButton: true,
                    confirmButtonColor: '#0168fa',
                    cancelButtonText: 'Close',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{!! route('data-statistik.downloadUpload') !!}";
                    }
                });
            });



            @php
                session()->forget('checksum-statistik');
            @endphp
        @endif

        $(document).on('click', '.edit-row', function() {
            let thiss = $(this);
            $(".td-wilayah").html(thiss.attr('data-wilayah'));
            $("#labelWilayah").html(thiss.attr('data-wilayah'));
            $("#labelSektor").html(thiss.attr('data-bidang'));
            $("#pop_id").val(thiss.attr('data-id'));
            $("#pop_nilai").val(thiss.attr('data-nilai'));
            $("#pop_wilayah").val(thiss.attr('data-idwilayah')).trigger('change');
            $("#pop_sektor").val(thiss.attr('data-idbidang')).trigger('change');
            $(".edit-update").prop('disabled', false).html(`<i class="fas fa-disc"></i> Save`);
            $("#modalNilai").modal('show');
        });

        $(document).on('click', '.btn-upload', function() {
            let file = $('input[name="file"]').val();
            if (file.length < 3) {
                Swal.fire({
                    text: 'No file uploaded!',
                    icon: 'warning',
                    confirmButtonColor: '#0168fa'
                })
                return false;
            }
            $("#modalUpload .pop-upload").prop('disabled', true)
            $("#modalUpload .btn-upload").html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Progress...'
            );
            $("#formupload").submit();
        });

        $(document).on('click', '.btn-publish', function() {
            let tahun = $("#tahun_data").val();

            if (tahun > 0) {
                Swal.fire({
                    text: 'Publish data upload untuk tahun data ' + tahun + '?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Publish',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#0168fa',
                    cancelButtonColor: '#dc3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{!! route('data-statistik.publish') !!}",
                            method: "post",
                            data: {
                                _token: '{!! csrf_token() !!}',
                                tahun_data: tahun,
                            },
                            beforeSend: function() {
                                $(".btn-publish").prop('disabled', true).html(
                                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Progress...`
                                );
                            },
                            success: function(result) {
                                if (result.valid == true) {
                                    Swal.fire({
                                        title: result.message,
                                        html: "<p>Data terpublish : " + result.data
                                            .data_terpublish +
                                            "</p><p>Data terupdate : " + result.data
                                            .data_terganti +
                                            "</p><p>Data terinput : " +
                                            result.data.data_terinput + "</p>",
                                        icon: 'success',
                                        confirmButtonColor: '#0168fa'
                                    })
                                    $("#dataTableBuilder_wrapper a.buttons-reload").click();
                                } else {
                                    Swal.fire({
                                        title: result.message,
                                        input: 'radio',
                                        inputOptions: {
                                            "force": "Update Paksa",
                                            "pass": "Lewatkan Data",
                                        },
                                        confirmButtonColor: '#0168fa',
                                        showCancelButton: true,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            $.ajax({
                                                url: "{!! route('data-statistik.publish') !!}?force=" +
                                                    (result.value == "force" ?
                                                        'true' : 'false'),
                                                method: "post",
                                                data: {
                                                    _token: '{!! csrf_token() !!}',
                                                    tahun_data: tahun,
                                                },
                                                beforeSend: function() {
                                                    $(".btn-publish").prop(
                                                        'disabled', true
                                                    ).html(
                                                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Progress...`
                                                    );
                                                },
                                                success: function(result) {
                                                    Swal.fire({
                                                        title: result
                                                            .message,
                                                        html: "<p>Data terpublish : " +
                                                            result
                                                            .data
                                                            .data_terpublish +
                                                            "</p><p>Data terupdate : " +
                                                            result
                                                            .data
                                                            .data_terganti +
                                                            "</p><p>Data terinput : " +
                                                            result
                                                            .data
                                                            .data_terinput +
                                                            "</p>",
                                                        icon: 'success',
                                                        confirmButtonColor: '#0168fa'
                                                    })
                                                    $("#dataTableBuilder_wrapper a.buttons-reload")
                                                        .click();
                                                    $("button.btn-publish")
                                                        .prop('disabled',
                                                            false).html(
                                                            `<i class="fas fa-disc"></i> Publish Data`
                                                            );

                                                },
                                                error: function(result) {
                                                    Swal.fire({
                                                        text: result
                                                            .message,
                                                        icon: 'warning',
                                                        confirmButtonColor: '#0168fa'
                                                    })
                                                    $("button.btn-publish")
                                                        .prop('disabled',
                                                            false).html(
                                                            `<i class="fas fa-disc"></i> Publish Data`
                                                        );
                                                }
                                            })
                                        }
                                    });
                                }
                                $("button.btn-publish").prop('disabled', false).html(
                                    `<i class="fas fa-disc"></i> Publish Data`);
                            },
                            error: function(result) {
                                Swal.fire({
                                    text: result.message,
                                    icon: 'warning',
                                    confirmButtonColor: '#0168fa'
                                })
                            }
                        });
                    }
                })
            } else {
                Swal.fire({
                    text: 'Tahun data harus dipilih!',
                    icon: 'warning',
                    confirmButtonColor: '#0168fa'
                })
            }
        })
    </script>
    <script>
        $(function() {
            $(document).on('click', '.btn-truncate', function(e) {
                Swal.fire({
                    title: 'Yakin hapus/reset data upload?',
                    text: 'Data upload akan di hapus semua dari sistem',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#0168fa',
                    cancelButtonColor: '#dc3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{!! route('data-statistik.deleteUpload') !!}",
                            method: "post",
                            data: {
                                _token: '{!! csrf_token() !!}',
                            },
                            beforeSend: function() {
                                $(".btn-truncate").prop('disabled', true).html(
                                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Progress delete`
                                );
                            },
                            success: function(result) {
                                if (result.valid == true) {
                                    Swal.fire({
                                        text: result.message,
                                        icon: 'success',
                                        confirmButtonColor: '#0168fa'
                                    })
                                    $("#dataTableBuilder_wrapper a.buttons-reload")
                                        .click();
                                    $(".btn-truncate").prop('disabled', false).html(
                                        `<i class="fas fa-trash"></i> &nbsp;Hapus/Reset Data Upload`
                                    );
                                } else {
                                    Swal.fire({
                                        text: result.valid,
                                        icon: 'warning',
                                        confirmButtonColor: '#0168fa'
                                    })
                                }
                            }
                        });
                    }
                })
            });
        });
    </script>
@endsection
