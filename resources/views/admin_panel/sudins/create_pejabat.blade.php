@extends('layouts.app')

@section('contents')
    <div class="content">
        <div class="container">
            @include('dashforge-templates::common.errors')

            <h4 id="section1" class="mg-b-10">Pejabat Suku Dinas {{ $wilayah->nama }}</h4>
            <div style="margin-right: -15px;margin-left: -15px;">
                <div data-label="Edit" class="df-example demo-forms services-forms">
                    {!! Form::open(['route' => 'panel.pejabat_sudins.store', 'files' => true]) !!}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card bd bd-light">
                                <div class="card-header bg-light"><h6>Suku Dinas</h6></div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Id Suku Dinas Field -->
                                        <div class="form-group col-sm-5">
                                            {!! Form::label('id_suku_dinas', 'Suku Dinas:') !!}
                                            {!! Form::select('id_suku_dinas', @$sukudinas, null, ['class' => 'form-control select2',
                                            'required']) !!}
                                        </div>

                                        <!-- Id Jabatan Field -->
                                        <div class="form-group col-sm-3">
                                            {!! Form::label('id_jabatan', 'Jabatan:') !!}
                                            {!! Form::select('id_jabatan', $jabatan, null, [
                                                'class' => 'form-control select2',
                                                'required',
                                                'placeholder' => 'Pilih Jabatan',
                                            ]) !!}
                                            {!! Form::hidden('id_wilayah', @$wilayah->id) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                                                      
                            <div class="card bd bd-light mg-t-20">
                                <div class="card-header bg-light"><h6>Info Pejabat</h6></div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <!-- Foto Field -->
                                            <div class="form-group col-sm-12">
                                                {!! Form::label('foto', 'Foto:', ['class' => 'd-block']) !!}
                                                {!! Form::file('foto', [
                                                    'class' => 'form-control dropify',
                                                    'data-default-file' => @$pejabatsudin->foto ? getFileUrl(@$pejabatsudin->foto) : '',
                                                    'data-max-file-size' => '2M',
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <!-- Nama Lengkap Field -->
                                            <div class="form-group col-sm-10">
                                                {!! Form::label('nama_lengkap', 'Nama Lengkap:', ['class' => 'd-block']) !!}
                                                {!! Form::text('nama_lengkap', null, ['class' => 'form-control', 'required']) !!}
                                            </div>
                        
                                            <!-- NIP Lengkap Field -->
                                            <div class="form-group col-sm-10">
                                                {!! Form::label('nip', 'NIP:', ['class' => 'd-block']) !!}
                                                {!! Form::text('nip', null, ['class' => 'form-control']) !!}
                                            </div>
                        
                                            <!-- Contact Lengkap Field -->
                                            <div class="form-group col-sm-10">
                                                {!! Form::label('contact', 'Hp/Mobile:', ['class' => 'd-block']) !!}
                                                <div class="input-group mg-b-10">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">+62</span>
                                                    </div>
                                                    {!! Form::number('contact', null, ['class' => 'form-control']) !!}
                                                </div>
                                            </div>
                        
                                            <!-- Email Lengkap Field -->
                                            <div class="form-group col-sm-10">
                                                {!! Form::label('email', 'Alamat Email:', ['class' => 'd-block']) !!}
                                                <div class="input-group mg-b-10">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                    </div>
                                                    {!! Form::email('email', null, ['class' => 'form-control']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <!-- Tahun Field -->
                                            <div class="form-group col-sm-12">
                                                {!! Form::label('tahun', 'Masa Jabatan (Tahun):') !!}
                                                <div class="input-group mg-b-10">
                                                    {!! Form::number('tahun[]', date('Y'), [
                                                            'class' => 'form-control date',
                                                            'required',
                                                            'min' => '1945',
                                                            'max' => date('Y')+4,
                                                            'required',
                                                    ]) !!}
                                                    <div class="input-group-append">
                                                    </div>
                                                </div>
                                                <div class="input-group mg-b-10" id="year-2">
                                                    {!! Form::number('tahun[]', date('Y')+1, [
                                                            'class' => 'form-control date',
                                                            'required',
                                                            'min' => '1945',
                                                            'max' => date('Y')+4,
                                                            'required',
                                                    ]) !!}
                                                    <div class="input-group-append">
                                                        <span class="input-group-text btn btn-outline-danger del-year" id="2"><i class="fas fa-times"></i></span>
                                                    </div>
                                                </div>
                                                <div class="input-group mg-b-10" id="year-3">
                                                    {!! Form::number('tahun[]', date('Y')+2, [
                                                            'class' => 'form-control date',
                                                            'required',
                                                            'min' => '1945',
                                                            'max' => date('Y')+4,
                                                            'required',
                                                    ]) !!}
                                                    <div class="input-group-append">
                                                        <span class="input-group-text btn btn-outline-danger del-year" id="3"><i class="fas fa-times"></i></span>
                                                    </div>
                                                </div>
                                                <div class="input-group mg-b-10" id="year-4">
                                                    {!! Form::number('tahun[]', date('Y')+3, [
                                                            'class' => 'form-control date',
                                                            'required',
                                                            'min' => '1945',
                                                            'max' => date('Y')+4,
                                                            'required',
                                                    ]) !!}
                                                    <div class="input-group-append">
                                                        <span class="input-group-text btn btn-outline-danger del-year" id="4"><i class="fas fa-times"></i></span>
                                                    </div>
                                                </div>
                                                <div class="input-group mg-b-10" id="year-5">
                                                    {!! Form::number('tahun[]', date('Y')+4, [
                                                            'class' => 'form-control date',
                                                            'required',
                                                            'min' => '1945',
                                                            'max' => date('Y')+4,
                                                            'required',
                                                    ]) !!}
                                                    <div class="input-group-append">
                                                        <span class="input-group-text btn btn-outline-danger del-year" id="5"><i class="fas fa-times"></i></span>
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
                        <a href="{!! route('panel.pejabat_sudins.index') !!}" class="btn btn-outline-light rounded-pill"><i class="fas fa-ban"></i> Batal</a>
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
            
            var cleave = new Cleave('#telp_pic', {
                phone: true,
                phoneRegionCode: 'ID'
            });
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