@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pejabat Pemerinta Daerah</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">Pejabat Pemerinta {!! strtoupper(@$wilayah->tipe) !!} - {!! @$wilayah->nama !!} </h4>

            <p class="mg-b-30">
                This is a list of your <code>Pejabat Pemerintah {!! strtoupper(@$wilayah->tipe) !!} - {!! @$wilayah->nama !!} </code>, you can manage by clicking on action
                buttons in this table.
            </p>

            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <h3>{!! strtoupper(@$wilayah->tipe) !!} - {!! @$wilayah->nama !!} </h3>
                </div>

                <div class="d-md-block">
                    <a class="btn btn-sm btn-dark btn-uppercase" href="{!! route('pejabatwilayahs.index') !!}"><i
                            class="fa fa-chevron-left"></i> Data Propinsi/Kab/Kota </a>

                    @can('pejabatwilayah-create')
                        <a class="btn btn-sm btn-primary btn-uppercase" href="{!! route('pejabatwilayahs.create', @$wilayah->id) !!}"><i
                                class="fa fa-plus"></i> Add New</a>
                    @endcan

                    @can('pejabatwilayah-create')
                        <a class="btn btn-sm btn-primary btn-uppercase" href="{!! route('pejabatwilayahs.create_excel', @$wilayah->id) !!}"><i
                                class="fa fa-upload"></i> Upload File Excel</a>
                    @endcan
                </div>
            </div>

            <div class="table-responsive">
                {!! $dataTable->table(['width' => '100%'], true) !!}
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('styles')
    @include('layouts.datatables_css')
    <style>
        .error-row {
            background-color: #ffcccc !important;
            /* Replace with your desired background color */
        }
    </style>
@endsection


@section('scripts')
    @include('layouts.datatables_js')
    {!! $dataTable->scripts() !!}

    <script>
        $(function() {
            $(document).on('click', '.table-del', function(e) {
                let id = $(this).data('id');
                let name = $(this).data('name');
                Swal.fire({
                    text: 'Yakin hapus data?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#0168fa',
                    cancelButtonColor: '#dc3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#table-form-" + id).submit();
                    }
                })
            });

            $(document).on('click', '.img-upload', function(e) {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Upload Foto Pejabat',
                    input: 'file',
                    showCancelButton: true,
                    inputAttributes: {
                        "accept": "image/*",
                        "aria-label": "Upload Foto Upload",
                        "class": "swal2-file"
                    },

                }).then((file) => {
                    if (file.value) {

                        const reader = new FileReader();
                        reader.onload = (e) => {
                            Swal.fire({
                                title: "Your uploaded picture",
                                imageUrl: e.target.result,
                                imageAlt: "The uploaded picture"
                            });
                        };
                        var formData = new FormData();
                        var file = $('.swal2-file')[0].files[0];
                        console.log(file)
                        formData.append("file", file);
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'post',
                            url: `${id}/img/upload`,
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(resp) {
                                Swal.fire('Uploaded', 'Your file have been uploaded',
                                    'success');
                                $('.buttons-reset').click();
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Oops...',
                                    text: 'Something went wrong!'
                                })
                            }
                        })
                    }
                });
            });
        });
    </script>
@endsection
