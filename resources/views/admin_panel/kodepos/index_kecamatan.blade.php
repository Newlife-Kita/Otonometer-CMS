@extends('layouts.app')

@section('styles')
    @include('layouts.datatables_css')
@endsection

@section('contents')
    <div class="content">
        <div class="container">
            @include('flash::message')
            <h4 id="section1" class="mg-b-10">Data Kecamatan {{ @$wilayah->nama }}</h4>
            <div class="table-responsive">
                {!! $dataTable->table(['width' => '100%'], true) !!}
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('scripts')
    @include('layouts.datatables_js')
    {!! $dataTable->scripts() !!}

    <script>
        $(function(){
            $(document).on('click', '.table-del', function(e){
                let id = $(this).data('id');
                let name = $(this).data('name');
                Swal.fire({
                    title: "Yakin hapus data?",
                    text: 'Hapus data ini akan menghapus data kelurahan di dalamnya',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#0168fa',
                    cancelButtonColor: '#dc3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#table-form-"+id).submit();
                    }
                })
            });
        });
    </script>
@endsection
