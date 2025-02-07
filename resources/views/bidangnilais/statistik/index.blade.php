@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data Sektor/Bidang Statistik</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">Data Sektor/Bidang Statistik</h4>

            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div class="wd-40p pd-t-20">
                    <div class="input-group mg-b-10 d-flex flex-wrap flex-column">
                        <div class="mg-b-10 w-75">
                            <div class="input-group-prepend w-100">
                                <span class="input-group-text w-100">Tahun Data</span>
                            </div>
                            <div class="input-group-append">
                                {!! Form::select('tahun', $tahun, null, [
                                    'class' => 'form-control select2',
                                    'id' => 'tahun',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                        <div class="mg-b-10 w-75">
                            <div class="input-group-prepend w-100">
                                <span class="input-group-text w-100">Provinsi</span>
                            </div>
                            <div class="input-group-append">
                                {!! Form::select('province', $province, '', [
                                    'class' => 'form-control select2',
                                    'id' => 'province',
                                    'required',
                                ]) !!}
                            </div>

                        </div>

                        <div class="">
                            <button type="button" class="btn btn-success btn-download"><i class="fas fa-file-excel"></i>
                                Download Data</button>
                        </div>
                    </div>
                </div>

                <div class="d-md-block">
                    <a class="btn btn-sm btn-dark btn-uppercase" href="{!! route('data-statistik.templateExcel') !!}"><i
                            class="fa fa-download"></i> Downoad Template Excel</a>

                    @can('bidangnilai-create')
                        <a class="btn btn-sm btn-primary btn-uppercase" href="{!! route('data-statistik.uploadform') !!}"><i
                                class="fa fa-upload"></i> Upload & Publish Data</a>
                    @endcan
                    @can('bidangnilai-delete')
                        <a class="btn btn-sm btn-danger btn-uppercase" href="{!! route('data-statistik.deleteform') !!}"><i
                                class="fas fa-trash"></i> Hapus Data</a>
                    @endcan
                </div>
            </div>

            <hr />

            <div class="bd-1 bg-gray-300 pd-10">
                <div class="input-group mg-b-10">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Filter : </span>
                    </div>
                    <div class="input-group-append pd-l-15">
                        <span class="input-group-text">Tahun Data</span>
                    </div>
                    <div class="input-group-append">
                        {!! Form::select('tahun_data', $tahun, null, [
                            'class' => 'form-control select2',
                            'id' => 'tahun_data',
                            'required',
                        ]) !!}
                    </div>
                    <div class="input-group-append pd-l-15">
                        <span class="input-group-text">Sektor Statistik</span>
                    </div>
                    <div class="input-group-append wd-40p">
                        {!! Form::select('sektor', $sektor, null, [
                            'class' => 'form-control select2',
                            'id' => 'sektor',
                            'required',
                        ]) !!}
                    </div>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-success btn-filter"><i class="fas fa-search"></i> Filter
                            Data</button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-border table-striped table-hover table-result">
                    <thead class="thead-dark">
                        <tr>
                            <th>Peringkat</th>
                            <th>Prov/Kab/Kota</th>
                            <th>Nilai</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2" class="tx-center">Data Not Found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="d-md-block">
                @can('bidangnilai-publish')
                    {!! Form::button('<i class="fa fa-paper-plane"></i> Publish', [
                        'type' => 'button',
                        'class' => 'btn btn-sm btn-success btn-uppercase publish-buttons',
                        'id' => 'publish-update',
                        'disabled',
                    ]) !!}
                    {!! Form::button('<i class="fa fa-check"></i> Ceklist Semua', [
                        'type' => 'button',
                        'class' => 'btn btn-sm btn-success btn-uppercase publish-buttons',
                        'id' => 'check-all-update',
                        'disabled',
                    ]) !!}
                    {!! Form::button('<i class="fa fa-trash"></i> Batalkan Semua', [
                        'type' => 'button',
                        'class' => 'btn btn-sm btn-danger btn-uppercase publish-buttons',
                        'id' => 'delete-all-update',
                        'disabled',
                    ]) !!}
                @endcan
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalCatatan" role="dialog" tabindex="-1" data-keyboard="false" data-backdrop="static"
        aria-labelledby="exampleModalLabel3" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content tx-14">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel3">Catatan Untuk </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formupload" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <h5>Bidang : <span id="lbl_bidang"></span>
                                    <h5>
                                        {!! Form::hidden('pop_bidang', null, ['id' => 'pop_bidang']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <h5>Propinsi, Kab/Kota : <span id="lbl_wilayah"></span>, &nbsp;Tahun Data : <span
                                        id="lbl_tahun"></span>
                                    <h5>
                                        {!! Form::hidden('pop_wilayah', null, ['id' => 'pop_wilayah']) !!}
                                        {!! Form::hidden('pop_tahun', null, ['id' => 'pop_tahun']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <table class="table table-striped">
                                    <thead class="thead-dark">
                                        <tr>
                                            @foreach ($bahasa as $lg)
                                                <td>{{ $lg->label }} ({{ $lg->code }})</td>
                                            @endforeach
                                            <td>#</td>
                                        </tr>
                                    </thead>
                                    <tbody class=" box-notes"></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="{{ $bahasa->count() }}">
                                                <button class="btn btn-outline-primary" type="button"
                                                    id="button-new-notes"><i class="fas fa-plus"></i> Catatan</button>
                                                {!! Form::hidden('row_length', 0, ['id' => 'row_length']) !!}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="alert alert-warning d-none mg-t-10" id="pop-alert" role="alert"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary tx-13 pop-upload" data-dismiss="modal">
                            Close</button>
                        <button type="submit" class="btn btn-primary pop-update-catatan"><i class="fas fa-disc"></i>
                            Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .input-group .input-group-prepend .select2-container {
            width: 100% !important;
        }

        .input-group .input-group-append .select2-container {
            width: 100% !important;
        }

        .input-group .select2-container {
            width: 40% !important;
        }
    </style>
@endsection

@section('scripts')
    <script>
        @if (Auth::user()->can('bidangnilai-delete'))
            window.hasDeletePermission = true;
        @else
            window.hasDeletePermission = false;
        @endif

        @if (Auth::user()->can('bidangnilai-update'))
            window.hasUpdatePermission = true;
        @else
            window.hasUpdatePermission = false;
        @endif


        @if (Auth::user()->can('bidangnilai-publish'))
            window.hasPublishPermission = true;
        @else
            window.hasPublishPermission = false;
        @endif

        let bahasa = @json($bahasa->pluck('code'));
        let listCheck = [];

        let previousBidang = 0;
        let id_wil = 0;

        $(document).ready(function() {
            $(".select2").select2();
        });

        $('#modalDelete').on('show.bs.modal', function(e) {
            $("#tahun_pop").select2();
            $("#sektor_pop").select2();
        })
        $(document).on('click', '.btn-download', function() {
            var tahun = $("#tahun").val();
            var provinceId = $("#province").val()
            if (tahun < 1) {
                Swal.fire({
                    text: 'Tahun data harus dipilih? ' + tahun,
                    icon: 'warning',
                    confirmButtonColor: '#0168fa',
                })
            } else if (!provinceId) {
                location.href = `{!! route('data-statistik.download') !!}?tahun=` + tahun;
            } else {
                location.href = `{!! route('data-statistik.download.province') !!}?tahun=` + tahun + `&province_id=` + provinceId;
            }
        })

        $(document).on('click', '.btn-update-delete', function() {
            let tahun = $("#tahun_data").val();
            let bidang = $("#sektor").val();
            let id = this.id;

            if (tahun.length < 4) {
                Swal.fire({
                    text: 'Tahun data harus di pilih?',
                    icon: 'warning',
                })
                return;
            }
            if (bidang.length < 1) {
                Swal.fire({
                    text: 'Bidang/Sektor Statistik harus di pilih?',
                    icon: 'warning',
                })
                return;
            }

            $.ajax({
                method: "DELETE",
                url: "{!! route('data-statistik.deleteupdated') !!}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    tahun: tahun,
                    bidang: bidang,
                    wilayah: id,
                },
                success: function(result) {
                    if (result.valid) {
                        Swal.fire({
                            text: 'Berhassil menghapus nilai.',
                            icon: 'success',
                        }).then(function() {
                            // Trigger click event on .btn.filter
                            listCheck = listCheck.filter(element => element != id);
                            $('.btn-filter').click();
                        });
                    } else {
                        // Handle invalid result
                        Swal.fire({
                            text: 'Gagal menghapus nilai.',
                            icon: 'error',
                        });
                    }
                }
            })
        })

        //button catatan di klik untuk merubah catatan per daerah/bidang dan tahun
        $(document).on('click', '.btn-notes', function() {
            let id = $(this).attr('data-id');
            let tahun = $("#tahun_data").val();
            let bidang = $("#sektor").val();
            let bidangNama = $("#sektor").find(":selected").text(); //.match(/\s(.*)$/)[1].trim();
            let namaWilayah = $(this).closest('tr').find('td').eq(1).text();

            $("#pop_bidang").val(bidang);
            $("#pop_tahun").val(tahun);
            $("#pop_wilayah").val(id);
            $("#lbl_tahun").html(tahun);
            $("#lbl_bidang").html(bidangNama);
            $("#lbl_wilayah").html(namaWilayah);

            if (tahun.length < 4) {
                Swal.fire({
                    text: 'Tahun data harus di pilih?',
                    icon: 'warning',
                })
                return;
            }
            if (bidang.length < 1) {
                Swal.fire({
                    text: 'Bidang/Sektor Statistik harus di pilih?',
                    icon: 'warning',
                })
                return;
            }
            $("#modalCatatan").modal('show');
        })

        $('#modalCatatan').on('show.bs.modal', function(e) {
            let tahun = $("#tahun_data").val();
            let bidang = $("#sektor").val();
            let id = $("#pop_wilayah").val();
            $.ajax({
                url: "{!! route('bidangcatatan.preview') !!}",
                data: {
                    tahun: tahun,
                    bidang: bidang,
                    wilayah: id,
                },
                success: function(result) {
                    if (result.valid == true) {
                        let t_row = 0;
                        $.each(result.data, function(ind, val) {
                            let field_note = `<tr id="pop_row_` + t_row +
                                `">{!! Form::hidden('id_pop_catatan[]', '`+val.id+`', [
                                    'class' => 'id_pop_catatan',
                                    'id' => 'id_pop_catatan_`+t_row+`',
                                ]) !!}`;
                            $.each(bahasa, function(index, value) {
                                $.each(val.catatan, function(index2, value2) {
                                    if (value == index2) {
                                        field_note +=
                                            `<td>{!! Form::text('pop_catatan[][`+value+`]', '`+value2+`', [
                                                'class' => 'form-control pop_catatan',
                                                'placeholder' => 'Catatan Data (`+value+`)',
                                                'id' => 'pop_catatan_`+t_row+`_`+value+`',
                                                'data-id' => '`+t_row+`',
                                            ]) !!}</td>`;
                                    }
                                });
                            });
                            field_note +=
                                `<td><button class="btn btn-outline-danger btn-rounded btn-xs btn-icon button-del-notes" type="button" id="` +
                                t_row + `"><i class="fas fa-times-circle"></i></button></td>`;
                            $(".box-notes").append(field_note);
                            t_row++;
                        });
                        $("#row_length").val(t_row);
                    }
                }
            })
        })

        $('#modalCatatan').on('hidden.bs.modal', function(e) {
            $(".box-notes").html('');
            $("#row_length").val(0);
            $("#pop_bidang").val('');
            $("#pop_tahun").val('');
            $("#pop_wilayah").val('');
            $("#lbl_tahun").html('');
            $("#lbl_bidang").html('');
            $("#lbl_wilayah").html('');
        })

        $(document).on('click', '#button-new-notes', function(e) {
            let total_field = (parseInt($("#row_length").val()) + 1);
            let field_note = `<tr id="pop_row_` + total_field + `">{!! Form::hidden('id_pop_catatan[]', 0, ['class' => 'id_pop_catatan', 'id' => 'id_pop_catatan_`+total_field+`']) !!}`;
            $.each(bahasa, function(index, value) {
                field_note += `<td>{!! Form::text('pop_catatan[][`+value+`]', null, [
                    'class' => 'form-control pop_catatan',
                    'placeholder' => 'Catatan Data (`+value+`)',
                    'id' => 'pop_catatan_`+total_field+`_`+value+`',
                    'data-id' => '`+total_field+`',
                ]) !!}</td>`;
            });
            field_note +=
                `<td><button class="btn btn-outline-danger btn-rounded btn-xs btn-icon button-del-notes" type="button" id="` +
                total_field + `"><i class="fas fa-times-circle"></i></button></td>`;
            $(".box-notes").append(field_note);
            $("#row_length").val(total_field);
        })

        $(document).on('submit', '#formupload', function(event) {
            event.preventDefault(); // Prevent the default form submission behavior

            let wilayah = $("#pop_wilayah").val();
            let tahun = $("#pop_tahun").val();
            let bidang = $("#pop_bidang").val();
            let catatan = [];
            let valid = true;

            $("#pop-alert").addClass('d-none');

            $(".button-del-notes").each(function() {
                var id = $(this).attr('id');
                var push_note = {};
                $.each(bahasa, function(index, value) {
                    push_note[value] = $("#pop_catatan_" + id + "_" + value).val();
                    if ($("#pop_catatan_" + id + "_" + value).val().length < 3) {
                        valid = false;
                    }
                });
                catatan.push({
                    id: $("#id_pop_catatan_" + id).val(),
                    note: push_note
                });
            });

            if (!valid) {
                Swal.fire({
                    text: 'Catatan Tidak boleh kosong',
                    icon: 'warning',
                });
                return;
            } else {
                $.ajax({
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{!! route('bidangcatatan.submit') !!}",
                    data: {
                        tahun: tahun,
                        bidang: bidang,
                        wilayah: wilayah,
                        notes: catatan
                    },
                    success: function(result) {
                        Swal.fire({
                            text: 'Catatan berhasil disimpan',
                            icon: 'success',
                        });
                        $('#modalCatatan').modal('hide');
                        return;
                    }
                });
            }
        });


        $(document).on('click', '.button-del-notes', function() {
            let id = $(this).attr('id');

            Swal.fire({
                title: "Yakin hapus catatan data?",
                showCancelButton: true,
                confirmButtonText: "Hapus",
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $("#pop_row_" + id).fadeOut().remove();
                }
            });
        })

        $(document).on('click', '.btn-update', async function() {
            let tahun = $("#tahun_data").val();
            let bidang = $("#sektor").val();
            let bidangNama = $("#sektor").find(":selected").text().match(/\s(.*)$/)[1].trim();
            let namaWilayah = $(this).closest('tr').find('td').eq(1).text();
            let id = this.id;

            if (tahun.length < 4) {
                Swal.fire({
                    text: 'Tahun data harus di pilih?',
                    icon: 'warning',
                })
                return;
            }
            if (bidang.length < 1) {
                Swal.fire({
                    text: 'Bidang/Sektor Statistik harus di pilih?',
                    icon: 'warning',
                })
                return;
            }

            nominal = await Swal.fire({
                title: "Ubah Nilai " + bidangNama + " " + namaWilayah,
                input: "text",
                showCancelButton: true,
                preConfirm: (value) => {
                    // Format input value as money
                    let formattedValue = parseFloat(value.replace(/,/g, '.'));

                    // Format with Indonesian Rupiah
                    if (isNaN(formattedValue)) {
                        Swal.showValidationMessage('Harap masukkan angka.');
                        return false;
                    }

                    // Update input field with formatted value
                    Swal.getInput().value = formattedValue;

                }
            })

            if (nominal.value) {
                $.ajax({
                    method: "PATCH",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{!! route('data-statistik.updatedata') !!}",
                    data: {
                        tahun: tahun,
                        bidang: bidang,
                        wilayah: id,
                        nilai: nominal.value
                    },
                    success: function(result) {
                        if (result.valid) {
                            Swal.fire({
                                text: 'Berhassil menambahkan nilai baru.',
                                icon: 'success',
                            }).then(function() {
                                // Trigger click event on .btn.filter
                                $('.btn-filter').click();
                            });
                        } else {
                            // Handle invalid result
                            Swal.fire({
                                text: 'Gagal menambahkan nilai baru.',
                                icon: 'error',
                            });
                        }
                    }
                })
            }
        })

        $(document).on('click', '.check-update', function() {
            let id = Number(this.id);

            if (listCheck.indexOf(id) == -1) {
                listCheck.push(id);
                $(this).removeClass('btn-outline-success');
                $(this).addClass('btn-success');
            } else {
                listCheck = listCheck.filter(element => element != id);
                $(this).removeClass('btn-success');
                $(this).addClass('btn-outline-success');
            }

            const tdValue = $('tr td:nth-child(4)');

            let arrayTemp = [];
            tdValue.each(function() {
                if ($(this).find('span:first-child').text() != "") {
                    arrayTemp.push(Number($(this).find('span:nth-child(2) button:nth-child(3)').attr(
                        'id')));
                }
            })

            var isEveryIncluded = arrayTemp.every(function(obj) {
                return listCheck.includes(obj);
            });

            if (!isEveryIncluded) {
                $('#check-all-update').addClass('btn-success').removeClass(
                    'btn-outline-success');
                $("#check-all-update").find('i').removeClass('fa-times').addClass(
                    'fa-check');
                $('#check-all-update').contents().filter(function() {
                    return this.nodeType === 3; // Filter text nodes
                }).first().replaceWith(" Ceklist Semua");
            } else {
                $('#check-all-update').addClass('btn-outline-success').removeClass(
                    'btn-success');
                $("#check-all-update").find('i').removeClass('fa-check').addClass(
                    'fa-times');
                $('#check-all-update').contents().filter(function() {
                    return this.nodeType === 3; // Filter text nodes
                }).first().replaceWith(" Unceklist Semua");
            }


        })

        $(document).on('click', '#check-all-update', function() {
            const spanTexts = $('tr td:nth-child(4) span:first-child');

            if ($(this).hasClass('btn-success')) {

                $(this).find('i').removeClass('fa-check').addClass('fa-times');
                $(this).contents().filter(function() {
                    return this.nodeType === 3; // Filter text nodes
                }).first().replaceWith(" Unceklist Semua");
                spanTexts.each(function() {
                    if ($(this).text() != "") {

                        let check = $(this).next('span').find('.check-update');

                        listCheck.push(Number(check.attr('id')));

                        check.addClass('btn-success').removeClass('btn-outline-success');

                    }

                })

                $(this).removeClass('btn-success').addClass('btn-outline-success');
            } else {
                $(this).find('i').removeClass('fa-times').addClass('fa-check');
                $(this).contents().filter(function() {
                    return this.nodeType === 3; // Filter text nodes
                }).first().replaceWith(" Ceklist Semua");
                spanTexts.each(function() {
                    if ($(this).text() != "") {

                        let check = $(this).next('span').find('.check-update');

                        check.addClass('btn-outline-success').removeClass('btn-success');

                    }

                })

                listCheck = [];

                $(this).removeClass('btn-outline-success').addClass('btn-success');
            }

        })

        $(document).on('click', '#publish-update', function() {
            let tahun = $("#tahun_data").val();
            let bidang = $("#sektor").val();

            if (listCheck.length > 0) {
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: 'Anda akan mengupdate data hasil perubahan yang sudah dipilih.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, update data!',
                    cancelButtonText: 'Batalkan'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: "POST",
                            url: "{!! route('data-statistik.publishupdate') !!}",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                tahun: tahun,
                                bidang: bidang,
                                wilayah: listCheck
                            },
                            success: function(result) {
                                if (result.valid) {
                                    Swal.fire({
                                        text: 'Berhassil mengupdate data.',
                                        icon: 'success',
                                    }).then(function() {
                                        // Trigger click event on .btn.filter
                                        $('.btn-filter').click();
                                    });
                                } else {
                                    // Handle invalid result
                                    Swal.fire({
                                        text: 'Gagal mengupdate data.',
                                        icon: 'error',
                                    });
                                }
                            }

                        })
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire('Dibatalkan', 'Data batal diupdate', 'info');
                    }
                })
            } else {
                Swal.fire('Pengingat', 'Belum terdapat data yang dipilih', 'info');
            }

        })

        $(document).on('click', '#delete-all-update', function() {
            let tahun = $("#tahun_data").val();
            let bidang = $("#sektor").val();

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Anda akan menghapus seluruh data hasil perubahan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batalkan'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: "DELETE",
                        url: "{!! route('data-statistik.deleteupdatedall') !!}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            tahun: tahun,
                            bidang: bidang,
                        },
                        success: function(result) {
                            if (result.valid) {
                                Swal.fire({
                                    text: 'Berhassil menghapus data.',
                                    icon: 'success',
                                }).then(function() {
                                    // Trigger click event on .btn.filter
                                    $('.btn-filter').click();
                                });
                            } else {
                                // Handle invalid result
                                Swal.fire({
                                    text: 'Gagal menghapus data.',
                                    icon: 'error',
                                });
                            }
                        }
                    })
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire('Dibatalkan', 'Data batal dihapus', 'info');
                }
            })
        })


        function findObjectsWithUpdatedNilai(arr) {
            return arr.filter(obj => obj.updated_nilai !== null);
        }

        $(document).on('click', '.btn-filter', function() {
            let tahun = $("#tahun_data").val();
            let bidang = $("#sektor").val();

            if (tahun.length < 4) {
                Swal.fire({
                    text: 'Tahun data harus di pilih?',
                    icon: 'warning',
                })
                return;
            }
            if (bidang.length < 1) {
                Swal.fire({
                    text: 'Bidang/Sektor Statistik harus di pilih?',
                    icon: 'warning',
                })
                return;
            }
            $.ajax({
                url: "{!! route('data-statistik.getdata') !!}",
                data: {
                    tahun: tahun,
                    bidang: bidang
                },
                beforeSend: function() {
                    $('.table-result>tbody').html(`<tr><td class="tx-center" colspan="3"><div class="text-center"><div class="spinner-border">...</div>
                    </div></td></tr>`);
                    $(".btn-filter").prop('disabled', true).html(
                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Progress...`
                    );
                },
                success: function(result) {
                    var html_body = ``;
                    if (result.items.length > 0) {

                        let arrayTemp = findObjectsWithUpdatedNilai(result.items);

                        if (arrayTemp.length > 0) {
                            $('.publish-buttons').prop('disabled', false);

                            var isEveryIncluded = arrayTemp.every(function(obj) {
                                return listCheck.includes(obj.id_wilayah);
                            });

                            if (!isEveryIncluded) {
                                $('#check-all-update').addClass('btn-success').removeClass(
                                    'btn-outline-success');
                                $("#check-all-update").find('i').removeClass('fa-times').addClass(
                                    'fa-check');
                                $('#check-all-update').contents().filter(function() {
                                    return this.nodeType === 3; // Filter text nodes
                                }).first().replaceWith(" Ceklist Semua");
                            } else {
                                $('#check-all-update').addClass('btn-outline-success').removeClass(
                                    'btn-success');
                                $("#check-all-update").find('i').removeClass('fa-check').addClass(
                                    'fa-times');
                                $('#check-all-update').contents().filter(function() {
                                    return this.nodeType === 3; // Filter text nodes
                                }).first().replaceWith(" Unceklist Semua");
                            }
                        } else {
                            $('#check-all-update').addClass('btn-success').removeClass(
                                'btn-outline-success');
                            $("#check-all-update").find('i').removeClass('fa-times').addClass(
                                'fa-check');
                            $('#check-all-update').contents().filter(function() {
                                return this.nodeType === 3; // Filter text nodes
                            }).first().replaceWith(" Ceklist Semua");
                            $('.publish-buttons').prop('disabled', true);
                        }

                        $.each(result.items, function(index, value) {
                            let indexExist = previousBidang == bidang ? listCheck.indexOf(
                                Number(value.id_wilayah)) : -1;
                            let nominal = value.nilai;
                            let updatedNominal = value.updated_nilai;
                            html_body += `<tr style="width:100%">
                                <td class=" tx-left pd-r-10" style="width:5%">` + (index + 1) + `</td>
                                <td class=" tx-left pd-r-10" style="width:25%">` + value.nama + `</td>
                                <td class=" tx-left pd-r-10" style="width:35%">` + (nominal === null ? 'NULL' :
                                    formatMoney(nominal)) +
                                `</td>
                                <td class=" tx-left pd-r-10" style="width:100%;display:flex; justify-content:space-between;">
                                    <span style="margin-right: 5px">` +
                                (updatedNominal ? formatMoney(
                                    updatedNominal) : "") +
                                `</span><span>` + (value.history_updated ?
                                    `<button type="button" class="btn btn-xs btn-outline-primary btn-icon" data-container="body" data-toggle="popover" data-placement="left" data-content="` +
                                    value.history_updated + `" style="margin-right: 5px">
                                        <i class="fa fa-info"></i></button>` : '') +
                                `<button class="btn-notes btn btn-xs btn-outline-success btn-icon" style="margin-right: 5px" data-id="` +
                                value.id_wilayah +
                                `"><i class="fas fa-clipboard"></i> Catatan</button> <button class="btn-update btn btn-xs btn-outline-primary btn-icon" style="margin-right: 5px" id="` +
                                value.id_wilayah +
                                `"` + (window.hasUpdatePermission ? "" :
                                    "disabled"
                                ) +
                                `><i class="fa fa-edit"></i></button><button class="btn-update-delete btn btn-xs btn-outline-danger btn-icon" style="margin-right: 5px"  id="` +
                                value
                                .id_wilayah +
                                `"` + (window.hasDeletePermission &&
                                    updatedNominal ? "" :
                                    "disabled"
                                ) + `><i class="fa fa-trash "></i></button>` + (
                                    window
                                    .hasPublishPermission ?
                                    '<button class="check-update btn btn-xs ' + (indexExist != -
                                        1 ?
                                        'btn-success' : 'btn-outline-success') +
                                    ' btn-icon" type="button" id="' +
                                    value.id_wilayah + '"' + (updatedNominal ?
                                        "" :
                                        "disabled"
                                    ) + '><i class="fa fa-check"></button>' :
                                    "") + `</span></td>`;
                            html_body += `</tr>`;
                        });

                    } else {
                        html_body +=
                            `<tr><td class="tx-center" colspan="3">Data not found</td></tr>`;


                    }

                    if (previousBidang != bidang) {
                        listCheck = [];
                        previousBidang = bidang;
                    }

                    $('.table-result>tbody').html(html_body);

                    $('[data-toggle="popover"]').popover();

                    $(".btn-filter").prop('disabled', false).html(
                        `<i class="fas fa-search"></i> Filter Data`);
                }
            });
        })
    </script>
@endsection
