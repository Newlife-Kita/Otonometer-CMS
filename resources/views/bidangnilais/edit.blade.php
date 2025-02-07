@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Nilai Sektor/Bidang</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">Edit Nilai Sektor/Bidang</h4>

            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>

                </div>

                <div class="d-md-block">
                    <a class="btn btn-sm btn-dark btn-uppercase" href="{!! route('bidangnilais.index') !!}"><i class="fa fa-chevron-left"></i> Back</a>
                </div>
            </div>
            <div class="table-responsive">
                {!! $dataTable->table(['width' => '100%'], true) !!}
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            {!! Form::open(['route' => 'bidangnilais.submit', 'files' => true, 'class' => 'modal-content']) !!}  
                <div class="modal-header">
                    <h6 class="modal-title">Edit Nilai Sektor/Bidang</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class="form-group col-sm-4">
                        {!! Form::label('tahun', 'Tahun:') !!}
                        {!! Form::text('tahun', null, ['class' => 'form-control', 'disabled']) !!}
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::label('wilayah', 'Prov/Kab/Kota:') !!}
                        {!! Form::hidden('id_wilayah', null, ['id' => 'id_wilayah']) !!}
                        {!! Form::text('wilayah', null, ['class' => 'form-control', 'disabled']) !!}
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::label('bidang', 'Bidang/Sektor:') !!}
                        {!! Form::hidden('id_bidang', null, ['id' => 'id_bidang']) !!}
                        {!! Form::text('bidang', null, ['class' => 'form-control', 'disabled']) !!}
                    </div>
                    <div class="form-group col-sm-8">
                        {!! Form::label('nilai', 'Nilai Rill:') !!}
                        {!! Form::hidden('id', null, ['id' => 'id']) !!}
                        <div class="input-group mg-b-10">                            
                            {!! Form::number('nilai', null, ['class' => 'form-control']) !!}
                            <div class="input-group-append">
                              <span class="input-group-text" id="nilai_label"></span>
                            </div>
                        </div>
                          
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    {!! Form::button('Ubah Data', ['class' => 'btn btn-primary', 'id' => 'btn-pop-save']) !!}
                </div>
            {!! Form::close() !!}
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
            
            $(document).on('click', '.edit-row', function(e){
                var thos = $(this);
                $('#editModal #tahun').val(thos.attr('data-tahun'));
                $('#editModal #wilayah').val(thos.attr('data-wilayah'));
                $('#editModal #bidang').val(thos.attr('data-bidang'));
                $('#editModal #nilai').val(thos.attr('data-nilai'));  
                $('#editModal #id_wilayah').val(thos.attr('data-idwilayah'));
                $('#editModal #id_bidang').val(thos.attr('data-idbidang'));
                $('#editModal #id').val(thos.attr('data-id')); 
                $('#editModal #nilai_label').html(thos.attr('data-nilai2')); 
                 
                $('#editModal').modal('show');
            });            

            $(document).on('click', '#btn-pop-save', function(){
                $.ajax({
                    method: "POST",
                    url: "{{ route('bidangnilais.update') }}",
                    dataType: "json",
                    data: {
                        _token:'{{ csrf_token() }}',
                        id: $('#editModal #id').val(),
                        id_wilayah:$('#editModal #id_wilayah').val(),
                        id_bidang:$('#editModal #id_bidang').val(),
                        tahun:$('#editModal #tahun').val(),
                        nilai:$('#editModal #nilai').val()
                    },
                    beforeSend: function(){

                    },
                    success: function(res){
                        if(res.valid == true){
                            $("#dataTableBuilder_wrapper a.buttons-reload").click();
                            Swal.fire({
                                text: res.message,
                                icon: 'success',
                                confirmButtonColor: '#0168fa',
                                cancelButtonColor: '#dc3545',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('#editModal').modal('hide');
                                }
                            });
                        }
                        else{
                            Swal.fire({
                                text: res.message,
                                icon: 'error',
                                confirmButtonColor: '#0168fa',
                                cancelButtonColor: '#dc3545',
                            });
                        }
                    }
                });
            })

            $(document).on('focusout','#editModal #nilai', function(){
                let amount = $(this).val();
                $('#nilai_label').html(formatRupiah(amount, 2, ',', '.'));
            });
        });

        function formatRupiah(amount, decimalCount = 2, decimal = ".", thousands = ",") {
            try {
                decimalCount = Math.abs(decimalCount);
                decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

                const negativeSign = amount < 0 ? "-" : "";
                let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
                let j = (i.length > 3) ? i.length % 3 : 0;

                return negativeSign +
                (j ? i.substr(0, j) + thousands : '') +
                i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) +
                (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
            } 
            catch (e) {
                console.log(e)
            }
        };
    </script>
@endsection