@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pejabat Suku Dinas</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">Preview Pejabat Suku Dinas</h4>

            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <h5>{!! @$sudin->nama_sudin !!} </h5>
                    <h6>{!! @$sudin->wilayah->nama !!} </h6>
                </div>


            </div>

            <div class="table-responsive">
                {!! $dataTable->table(['width' => '100%'], true) !!}
            </div>

            <p class="mt-10 error-msg text-danger" style="display: none">
                Cannot save the data. Please complete or fix the data first.
            </p>

            <p class="mt-100 success-msg text-success" style="display: none">
                Everything is alright, you can savely save the data.
            </p>

            <div class="d-md-block">
                @can('pejabatwilayah-delete')
                    {!! Form::open([
                        'route' => ['pejabatsudins.cancel', $sudin->id],
                        'method' => 'delete',
                        'id' => 'cancel',
                        'class' => 'form-inline',
                    ]) !!}
                    {!! Form::button('<i class="fa fa-times"></i> Cancel', [
                        'type' => 'button',
                        'class' => 'btn btn-sm btn-danger btn-uppercase',
                        'id' => 'delete',
                    ]) !!}
                    {!! Form::close() !!}
                @endcan

                @can('pejabatwilayah-create')
                    {!! Form::open([
                        'route' => ['pejabatsudins.submit', $sudin->id],
                        'id' => 'save-data',
                        'class' => 'form-inline',
                    ]) !!}
                    {!! Form::button('Submit <i class="fa fa-paper-plane"></i>', [
                        'type' => 'button',
                        'class' => 'btn btn-sm btn-success btn-uppercase',
                        'id' => 'saveBtn',
                        'disabled',
                    ]) !!}
                    {!! Form::close() !!}
                @endcan
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

        .form-inline {
            display: inline-block;
            /* Add any additional styles as needed */
        }
    </style>
@endsection


@section('scripts')
    @include('layouts.datatables_js')
    {!! $dataTable->scripts() !!}

    <script>
        $(function() {
            $(document).on('click', '#delete', function(e) {
                Swal.fire({
                    text: 'Yakin batalkan upload?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Batalkan',
                    cancelButtonText: 'Kembali',
                    confirmButtonColor: '#0168fa',
                    cancelButtonColor: '#dc3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#cancel").submit();
                    }
                });
            });

            $(document).on('click', '#saveBtn', function(e) {
                Swal.fire({
                    text: 'Yakin submit data?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#0168fa',
                    cancelButtonColor: '#dc3545',
                }).then((result) => {
                    console.log(result);
                    console.log($("#save-data"));
                    if (result.isConfirmed) {
                        console.log('hai');
                        $("#save-data").submit();
                    }
                });
            });

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
                        formData.append("file", file);
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'post',
                            url: `{{ route('pejabatsudins.img_upload', ['id' => ':id']) }}`
                                .replace(':id', id),
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
