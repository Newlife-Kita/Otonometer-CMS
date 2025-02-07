@section('styles')
    @include('layouts.datatables_css')
    <style>
        .error-row{
            background-color: #ffcccc !important;
            /* Replace with your desired background color */
        }

        .form-inline {
            display: inline-block;
            /* Add any additional styles as needed */
        }
    </style>
    
@endsection

{!! $dataTable->table(['width' => '100%'], true) !!}

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
                        var formData = new FormData();
                        var file = $('.swal2-file')[0].files[0];
                        console.log(file)
                        formData.append("file", file);
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            method: 'post',
                            url: `{{ route('dprds.img_upload', ['id' => ':id']) }}`
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
