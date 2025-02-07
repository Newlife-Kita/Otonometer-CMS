@extends('layouts.app')

@section('styles')
    @include('layouts.datatables_css')
    <style>
        .input-group .select2{
            width: 55% !important;
        }
        .select2-container .select2-selection--single {
            height: 37px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 35px;
        }
    </style>
@endsection

@section('contents')
    <div class="content">
        <div class="container">
            @include('dashforge-templates::common.errors')

            <h4 id="section1" class="mg-b-10">Informasi {{ @$wilayah->nama }}/Tahun</h4>
            <div class="d-sm-flex align-items-center justify-content-between mg-b-5 mg-lg-b-5 mg-xl-b-10">
                <div></div>

                <div class="d-md-block">
                    @can('datawilayah-create')
                        <a class="btn btn-sm btn-outline-primary rounded-pill btn-new-info" data-toggle="modal" data-animation="effect-scale" href="#"><i class="fa fa-plus"></i> Informasi</a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive">
                {!! $dataTable->table(['width' => '100%'], true) !!}
            </div>
        </div>
    </div>
    <!-- /.content -->

    @canAny(['datawilayah-create','datawilayah-edit'])
    <div class="modal fade" id="modalDatawilayah" role="dialog" aria-labelledby="modalDatawilayah" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content tx-14">
            <div class="modal-header">
              <h6 class="modal-title" id="exampleModalLabel3"><span class="popup-label-info"></span> Informasi {{ @$wilayah->nama }}</h6>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            {!! Form::open(['id' => 'form_datawilayah']) !!}      
            <div class="modal-body">      

                <div class="alert alert-info mg-b-20">Gunakan tanda titik <b>(.)</b> sebagai pemisah desimal</div>
                <div class="form-group col-sm-3">
                    {!! Form::label('tahun', 'Tahun:', ['class' => 'd-block']) !!}
                    {!! Form::select(
                        'tahun',
                        $tahun,
                        null,
                        ['class' => 'form-control select2', 'id' => 'tahun']
                    ) !!}
                </div>  
                <div class="form-group col-sm-12">
                    {!! Form::label('id_sektor', 'PDRB Unggulan:', ['class' => 'd-block']) !!}
                    {!! Form::select(
                        'id_sektor',
                        $pdrb,
                        null,
                        ['class' => 'form-control select2', 'id' => 'id_sektor']
                    ) !!}
                </div>
                <div class="form-group col-sm-12">
                    {!! Form::label('nilai_sektor', 'Nilai PDRB:', ['class' => 'd-block']) !!}
                    <div class="input-group mg-b-10">          
                        {!! Form::number('nilai_sektor', NULL, ['class' => 'form-control tonumber', 'id' => 'nilai_sektor']) !!}              
                        <div class="input-group-append">
                          <span class="input-group-text"><span class="tx-bold" id="nilai_sektor_str"></span>&nbsp; Ribu/Kapita</span>
                        </div>
                    </div>
                </div>
                <div class="form-group col-sm-12">
                    {!! Form::label('ketinggian', 'Ketinggian: ') !!}   
                    <div class="input-group mg-b-10">                  
                        {!! Form::number('ketinggian', @$datawilayah->ketinggian, ['class' => 'form-control tonumber', 'id' => 'ketinggian']) !!}              
                        <div class="input-group-append">
                          <span class="input-group-text"><span class="tx-bold" id="ketinggian_str"></span>&nbsp; mpdl</span>
                        </div>
                    </div>
                </div>
                <div class="form-group col-sm-12">
                    {!! Form::label('luas_wilayah', 'Luas Wilayah:') !!}
                    <div class="input-group mg-b-10">       
                        {!! Form::number('luas_wilayah', @$datawilayah->luas_wilayah, ['class' => 'form-control tonumber', 'id' => 'luas_wilayah']) !!}    
                        <div class="input-group-append">
                            <span class="input-group-text"><span class="tx-bold" id="luas_wilayah_str"></span>&nbsp; Km<sup>2</sup></span>
                        </div>
                    </div>
                </div>
                <div class="form-group col-sm-12">
                    {!! Form::label('jumlah_penduduk', 'Jumlah Penduduk:') !!}
                    <div class="input-group mg-b-10">       
                        {!! Form::number('jumlah_penduduk', @$datawilayah->jumlah_penduduk, ['class' => 'form-control tonumber', 'id' => 'jumlah_penduduk']) !!}    
                        <div class="input-group-append">
                            <span class="input-group-text"><span class="tx-bold" id="jumlah_penduduk_str"></span>&nbsp;Jiwa</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {!! Form::hidden('nilai_id', @$datawilayah->id, ['id' => 'nilai_id']) !!}
                <button type="button" class="btn btn-outline-secondary rounded-pill tx-13" data-dismiss="modal"><i class="fas fa-ban"></i> Tutup</button>
                <button type="button" class="btn btn-outline-primary rounded-pill tx-13 btn-submit" data-dismiss="modal"><i class="fas fa-save"></i> Simpan</button>
            </div>
            {!! Form::close() !!}
          </div>
        </div>
    </div>
    @endcanAny
@endsection

@section('scripts')
    @include('layouts.datatables_js')
    {!! $dataTable->scripts() !!}

    @canAny(['datawilayah-create','datawilayah-edit'])
    <script>
        $(function(){
            $('.select2').select2();

            $('#modalDatawilayah').on('show.bs.modal', function (e) {
                $('#id_sektor').select2({
                    dropdownParent: $('#modalDatawilayah')
                });
                $('#tahun').select2({
                    dropdownParent: $('#modalDatawilayah')
                });
            })

            $('#modalDatawilayah').on('hidden.bs.modal', function (e) {
                $('#id_sektor').val(null).trigger('change');
                $('#tahun').val(null).trigger('change');
                $("#nilai_id").val(null);
            })

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

            $(document).on('click', '.btn-new-info', function(e){
                $("#id_sektor").val({{@$lastInfo->id_sektor}});
                $("#nilai_sektor").val(toNumber({{@$lastInfo->nilai_sektor}}));
                $("#ketinggian").val(toNumber({{@$lastInfo->ketinggian}}));
                $("#luas_wilayah").val(toNumber({{@$lastInfo->luas_wilayah}}));
                $("#jumlah_penduduk").val(toNumber({{@$lastInfo->jumlah_penduduk}}));
                $(".popup-label-info").html('Tambah');
                $('#modalDatawilayah').modal({
                    backdrop: 'static', 
                    keyboard: false
                })		
            });  

            $(document).on('click', '.btn-edit-row', function(e){
                let id = $(this).data('id');
                let tahun = $(this).data('tahun');
                let pdrb = $(this).data('pdrb');
                let nilai = $(this).data('nilai');
                let ketinggian = $(this).data('ketinggian');
                let luas = $(this).data('luas');
                let penduduk = $(this).data('penduduk');
                                
                $("#tahun").val(tahun);
                $("#id_sektor").val(pdrb);
                $("#nilai_sektor").val(toNumber(nilai));
                $("#ketinggian").val(toNumber(ketinggian));
                $("#luas_wilayah").val(toNumber(luas));
                $("#jumlah_penduduk").val(toNumber(penduduk));
                $("#nilai_id").val(id);
                $(".popup-label-info").html('Ubah');
                
                $("#nilai_sektor_str").html(nilai,2,',','.');
                $("#ketinggian_str").html(formatRupiah(ketinggian,2,',','.'));
                $("#luas_wilayah_str").html(formatRupiah(luas,2,',','.'));
                $("#jumlah_penduduk_str").html(formatRupiah(penduduk,'0',',','.'));
                $("#tahun").trigger('change');
                $('#modalDatawilayah').modal({
                    backdrop: 'static', 
                    keyboard: false
                })
            });    

            $(document).on('click', '.btn-submit', function(){
                $.ajax({
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('panel.datawilayahs.submit') }}",
                    data: $('#form_datawilayah').serialize(),
                    success: function(result) {
                        if(result.valid){
                            Swal.fire({
                                text: result.message,
                                icon: 'success',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('#modalDatawilayah').modal('hide')
                                }
                            })
                        }
                        else{
                            Swal.fire({
                                text: result.message,
                                icon: 'error',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('#modalDatawilayah').modal('hide')
                                }
                            })
                        }
                    }
                })
                    return false;
            })

            $(document).on('keyup','.tonumber', function(e){
                let val = $(this).val();
                let to_id = $(this).attr('id');    
                return get_number(val, to_id);            
            })

            $(document).on('focusout','.tonumber', function(e){
                let val = $(this).val();
                let to_id = $(this).attr('id');  

                return get_number(val.replace(",", "."), to_id);            
            })

            function get_number(val, idl){
                if(idl == 'jumlah_penduduk') place = 0;
                else place = 2;
                
                num = Number(val);
                console.log(num, Number.isFinite(num));
                if (Number.isFinite(num)){
                    $("#"+idl+"_str").html(formatRupiah(num, place,',','.'));
                }
                else{
                    $("#"+idl+"_str").html(0);
                }
            }

            function toNumber(string) {
                if(isNaN(string)){
                    return 0;
                }
                else return Number(string);
            };

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
                } catch (e) {
                    console.log(e)
                }
            };
        });
    </script>
    @endcanAny
@endsection
