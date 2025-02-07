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


        });
    </script>
@endsection
