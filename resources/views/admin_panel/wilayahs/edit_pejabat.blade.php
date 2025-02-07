@extends('layouts.app')

@section('contents')
    <div class="content">
        <div class="container">
            @include('dashforge-templates::common.errors')

            <h4 id="section1" class="mg-b-10">Pemerintah Daerah  {{ $wilayah->nama }}</h4>
            <div style="margin-right: -15px;margin-left: -15px;">
                <div data-label="Edit" class="df-example demo-forms services-forms">
                    {!! Form::model($pejabat, ['route' => ['panel.pejabat_wilayahs.update', $pejabat->id], 'method' => 'patch', 'files' => true]) !!}
                    <div class="row">
                        <div class="col-sm-12">                                                      
                            <div class="card bd bd-light mg-t-20">
                                <div class="card-header bg-light"><h6>Info Pemerintah Daerah</h6></div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <!-- Foto Field -->
                                            <div class="form-group col-sm-12">
                                                {!! Form::label('foto', 'Foto:', ['class' => 'd-block']) !!}
                                                {!! Form::file('foto', [
                                                    'class' => 'form-control dropify',
                                                    'data-default-file' => @$pejabat->foto ? getFileUrl(@$pejabat->foto) : '',
                                                    'data-max-file-size' => '2M',
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <!-- Nama Lengkap Field -->
                                            <div class="form-group col-sm-10">
                                                {!! Form::label('nama_lengkap', 'Nama Lengkap:', ['class' => 'd-block']) !!}
                                                {!! Form::text('nama_lengkap', @$pejabat->nama_lengkap, ['class' => 'form-control', 'required']) !!}
                                            </div>
                        
                                            <!-- Id Jabatan Field -->
                                            <div class="form-group col-sm-10">
                                                {!! Form::label('id_jabatan', 'Jabatan:') !!}
                                                {!! Form::select('id_jabatan', $jabatan, $pejabat->id_jabatan, [
                                                    'class' => 'form-control select2',
                                                    'required',
                                                    'placeholder' => 'Pilih Jabatan',
                                                ]) !!}
                                                {!! Form::hidden('id_wilayah', @$wilayah->id) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <!-- Periode Field -->
                                            <div class="form-group col-sm-12">
                                                <div class="input-group mg-b-10">
                                                    {!! Form::label('periode', 'Periode:', ['class' => 'pd-t-5']) !!}
                                                    <div class="mg-l-10">
                                                        <button type="button" class="btn btn-xs btn-outline-info btn-icon wd-30 rounded-pill" id="popup-periode" data-html="true" title="Penempatan Peride pada aplikasi" data-content="<img class='wd-100p' src='{{ asset('popup-image-periode-pemda.jpg') }}'><span class='d-block tx-center mg-t-5'>Periode adalah label yang di tampilkan pada posisi dibawah nama lengkap pejabat pada aplikasi/website</span>"><i class="fas fa-info"></i></button>
                                                    </div>
                                                </div>
                                                <div class="input-group mg-b-10">
                                                    {!! Form::number('tahun_lantik', $pejabat->tahun_lantik, [
                                                        'class' => 'form-control date',
                                                        'required',
                                                        'min' => '1945',
                                                        'max' => date('Y'),
                                                        'required',
                                                    ]) !!}
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="basic-addon2"> S.D </span>
                                                    </div>
                                                    {!! Form::number('tahun_akhir', $pejabat->tahun_akhir, [
                                                        'class' => 'form-control date',
                                                        'required',
                                                        'min' => '1945',
                                                        'max' => date('Y')+4,
                                                        'required',
                                                    ]) !!}
                                                </div>
                                            </div>

                                            <!-- Tahun Field -->
                                            <div class="form-group col-sm-12">
                                                <div class="input-group mg-b-10">
                                                    {!! Form::label('periode', 'Tahun Jabatan:', ['class' => 'pd-t-5']) !!}
                                                    <div class="mg-l-10">
                                                        <button type="button" class="btn btn-xs btn-outline-info btn-icon wd-30 rounded-pill" id="popup-tahun" data-html="true" title="Tahun Jabatan" data-content="<img class='wd-100p' src='{{ asset('popup-image-tahun-pemda.jpg') }}'><span class='tx-center d-block mg-t-5'>Tahun Jabatan adalah pencarian pejabat pada saat filter tahun dipilih pada aplikasi/website</span>"><i class="fas fa-info"></i></button>
                                                    </div>
                                                </div>
                                                <div class="input-group mg-b-10">
                                                    {!! Form::number('tahun', $pejabat->tahun, [
                                                            'class' => 'form-control date',
                                                            'required',
                                                            'min' => '1945',
                                                            'max' => date('Y')+4,
                                                            'required',
                                                    ]) !!}
                                                    <div class="input-group-append">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>  
                    <div class="row">
                        <div class="col-sm-8">   
                        </div>
                    </div>                 

                    <div class="clearfix"></div>
                    <hr>

                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        <button type="submit" class="btn btn-outline-primary rounded-pill"><i class="fas fa-save"></i> Simpan</button>
                        <a href="{!! route('panel.pejabat_wilayahs.index') !!}" class="btn btn-outline-light rounded-pill"><i class="fas fa-ban"></i> Batal</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection


@section('scripts')
    <script src="{{ asset('vendor/dashforge/lib/cleave.js/cleave.js') }}"></script>
    <script src="{{ asset('vendor/dashforge/lib/cleave.js/addons/cleave-phone.id.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".select2").select2();
            $('.dropify').dropify({
                messages: {
                    default: 'Drag and drop file here or click',
                    replace: 'Drag and drop file here or click to Replace',
                    remove: 'Remove',
                    error: 'Sorry, the file is too large'
                }
            });
            
            $('#popup-periode, #popup-tahun').popover({
                template: `<div class="popover popover-header-light" role="tooltip">
                    <div class="arrow"></div>
                    <h3 class="popover-header"></h3>
                    <div class="popover-body tx-primary"></div>
                </div>`
            })
        });

        $(document).on('click','.del-year',function() {
            let rowid = $(this).attr('id');
            Swal.fire({
                text: 'Yakin hapus masa jabatan?',
                icon: 'warning',
                showCloseButton: false,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: "Ya Hapus",
                cancelButtonText: "batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#year-"+rowid).fadeOut().remove();
                    return false;
                }
            });
        })
    </script>
    <!-- End Relational Form table -->
@endsection