@section('styles')
    @include('layouts.datatables_css')
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
                    title: 'Yakin Hapus data kecamatan?',
                    text: 'Menghapus data kecamatan otomatis menghapus data kelurahan di dalamnya',
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
        });
    </script>
@endsection
