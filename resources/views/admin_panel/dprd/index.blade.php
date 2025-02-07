@extends('layouts.app')

@section('styles')
    @include('layouts.datatables_css')
@endsection

@section('contents')
    <div class="content">
        <div class="container">
            @include('flash::message')
            <h4 id="section1" class="mg-b-30">Anggota DPRD {{ @$wilayah->nama }}</h4>
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>

                </div>

                <div class="d-md-block">
                    @can('sudin-create')
                    <a class="btn btn-sm btn-outline-primary rounded-pill" href="{!! route('panel.anggota_dprds.create') !!}"><i class="fa fa-plus"></i> Anggota DPRD</a>
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
                    text: 'Anda akan menghapus data pemimpin daerah!',
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
