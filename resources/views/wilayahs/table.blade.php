@section('styles')
    @include('layouts.datatables_css')
    <style>

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
                let desc = $(this).data('desc');
                Swal.fire({
                    title: name,
                    text: desc,
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

            var dataTable = $('#dataTableBuilder').DataTable();


            $('#tahun-select').change(function() {
                // This code will be executed when the value of the select element changes

                var selectedValue = parseInt($(this).val(), 10);
                dataTable.draw();

                console.log('Selected value changed to: ' + selectedValue);

            });
        });
    </script>
@endsection
