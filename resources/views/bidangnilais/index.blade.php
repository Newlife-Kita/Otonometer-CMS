@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Nilai Sektor/Bidang</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">Nilai Sektor/Bidang</h4>

            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>

                </div>

                <div class="d-md-block">
                    @can('bidangnilai-edit')
                        <a class="btn btn-sm btn-success btn-uppercase" href="{!! route('bidangnilais.edit') !!}"><i class="fa fa-edit"></i> Edit Data</a>
                    @endcan
                    
                    @can('bidangnilai-create')
                        <a class="btn btn-sm btn-primary btn-uppercase" href="{!! route('bidangnilais.create') !!}"><i class="fa fa-upload"></i> Upload File Excel</a>
                    @endcan
                </div>
            </div>

            <div class="mg-b-20 mg-lg-b-25 mg-xl-b-30 bd-1 bg-gray-300 pd-10">
                <div class="input-group mg-b-10">
                    <div class="input-group-prepend">
                        {!! Form::select('tahun', $tahun, null, [
                            'class' => 'form-control select2',
                            'required',
                            'placeholder' => 'Tahun',
                            'id' => 'tahun'
                        ]) !!}
                    </div>
                    {!! Form::select('id_lokasi', $wilayah, null, [
                        'class' => 'form-control select2',
                        'required',
                        'placeholder' => 'Pilih Propinsi',
                        'id' => 'id_lokasi'
                    ]) !!}
                    {!! Form::select('id_bidang', $bidang, null, [
                        'class' => 'form-control select2',
                        'required',
                        'placeholder' => 'Pilih Bidang/ Sektor',
                        'id' => 'id_bidang'
                    ]) !!}
                    <div class="input-group-append">
                        <button type="button" class="btn btn-dark" id="btn-filter">Filter</button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-border table-result">
                    <thead class="thead-dark">
                        <tr>
                            <th>Kode</th>
                            <th>Lokasi/ Daerah</th>
                            <th>Sektor/Bidang</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2" class="tx-center">Data Not Found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('styles')
    <style>
        .input-group .input-group-prepend .select2-container{ width: 100% !important;}
        .input-group .select2-container {
            width: 40% !important;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(function(){
            $(".select2").select2();
        });

        $(document).on('click', '#btn-filter', function(){
            let tahun = $("#tahun").val();
            let lokasi = $("#id_lokasi").val();
            let bidang = $("#id_bidang").val();  

            if(tahun.length < 4){
                Swal.fire({
                    text: 'Tahun harus di pilih?',
                    icon: 'warning',
                })
                return;
            }
            
            if(lokasi.length<1){
                Swal.fire({
                    text: 'Propinsi harus di pilih?',
                    icon: 'warning',
                })
                return;
            }
            
            if(bidang.length<1){
                Swal.fire({
                    text: 'Bidang/Sektor harus di pilih?',
                    icon: 'warning',
                })
                return;
            }

            $.ajax({
                url : "{!! route('bidangnilais.getdata') !!}",
                data: {
                    tahun : tahun,
                    lokasi: lokasi,
                    bidang:bidang
                },
                beforeSend: function(){
                },
                success: function(result){
                    $('.table-result>thead').html(``);
                    $('.table-result>tbody').html(``);
                    var html_head = `<tr>
                            <th>Kode</th>
                            <th>Daerah/Wilayah</th>`;
                    $.each(result.bidang, function( index, value ) {
                        html_head += `<th class="wd-150 tx-left pd-r-10">`+value.code+`. `+value.name+`</th>`;
                    });
                    html_head += `</tr></thead>`;
                    $('.table-result>thead').html(html_head);
                    
                    var html_body = ``;
                    $.each(result.items, function( index, value ) {
                        html_body += `<tr><th class="wd-50 tx-left pd-r-10">`+value.kode_lokasi+`</th>
                            <th class="wd-150 tx-left pd-r-10">`+value.nama_lokasi+`</th>`;

                        $.each(value.data, function( index2, value2 ) {
                            if(value2.nilai == null) var bd = 'tx-bold tx-danger';
                            else  var bd = '';
                            html_body += `<th class="wd-150 tx-left pd-r-10 `+bd+`">`+value2.nilai+`</th>`;
                        });
                        html_body += `</tr>`;
                    });
                    
                    $('.table-result>tbody').html(html_body);
                }
            });
        })
    </script>
@endsection
