@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sektor/Bidang Keuangan</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">Sektor/Bidang Keuangan</h4>

            <p class="mg-b-30">
                This is a list of your <code>Sektor/Bidang Keuangan</code>, you can manage by clicking on action buttons in this table.
            </p>

            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    @if(!empty(@$parent))
                        @if(!empty(@$parent->id_parent))
                            <h5>Childs Of {!! strtoupper(@$parent->kode) !!}.{!! @$parent->nama !!} </h5> 
                        @endif
                    @endif
 
                </div>
                <div class="d-md-block">
                    @if(!empty(@$subparent))
                        @if(!empty(@$subparent->id_parent))
                            <a class="btn btn-sm btn-dark btn-uppercase" href="{!! route('sektor-bidang.keuangan.child_index', $subparent->id) !!}"><i class="fa fa-chevron-left"></i> List Data {{ @$subparent->kode }}.  {{ @$subparent->nama }} </a>
                        @else
                            <a class="btn btn-sm btn-dark btn-uppercase" href="{!! route('sektor-bidang.keuangan.index') !!}"><i class="fa fa-chevron-left"></i> List Data {{ @$subparent->nama }} </a>
                        @endif
                    @else
                        <a class="btn btn-sm btn-success btn-uppercase" href="{!! route('sektor-bidang.keuangan.show',@$parent->id) !!}"><i class="fa fa-list"></i> Preview Sektor/Bidang Keuangan </a>
                    @endif

                    @can('bidang-create')
                        <a class="btn btn-sm btn-primary btn-uppercase" href="{!! route('sektor-bidang.keuangan.create', @$parent->id) !!}"><i class="fa fa-plus"></i> Add New</a>
                    @endcan
                </div>
            </div>

            <div class="table-responsive">
                {!! $dataTable->table(['width' => '100%'], true) !!}
            </div>
        </div>
    </div>
@endsection

@section('styles')
    @include('layouts.datatables_css')
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
                    text: 'Yakin hapus data?',
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
