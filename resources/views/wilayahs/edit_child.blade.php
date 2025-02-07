@extends('layouts.app')

@section('contents')
    <div class="content">
        <div class="container">
            @include('dashforge-templates::common.errors')

            <h4 id="section1" class="mg-b-10">Ubah Data Provinsi/Kabupaten/Kota</h4>

            <p class="mg-b-30">Please, fill all required fields before click save button.</p>

            <div style="margin-right: -15px;margin-left: -15px;">
                <div data-label="Edit" class="df-example demo-forms services-forms">
                    {!! Form::model($wilayah, ['route' => ['wilayahs.update', $wilayah->id], 'method' => 'patch', 'files' => true]) !!}
                    <div class="row">
                        <div class="col-sm-6">
                            <!-- Tipe Field -->
                            <div class="form-group col-sm-6">
                                {!! Form::label('parent', 'Provinsi:', ['class' => 'd-block']) !!}
                                {!! Form::text('parent', @$province->nama, ['class' => 'form-control', 'readonly']) !!}
                                {!! Form::hidden('parent_id', @$province->id) !!}
                            </div>
                            <!-- Tipe Field -->
                            <div class="form-group col-sm-6">
                                {!! Form::label('tipe', 'Kabupaten/Kota:', ['class' => 'd-block']) !!}
                                {!! Form::select('tipe', ['kabupaten' => 'kabupaten', 'kota' => 'kota'], null, ['class' => 'form-control select2']) !!}
                            </div>
                            
                            <!-- Kode Field -->
                            <div class="form-group col-sm-4">
                                {!! Form::label('kode', 'Kode:', ['class' => 'd-block']) !!}
                    
                                <div class="input-group mg-b-10">                                                       
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{ $province->id_increament }}.</span>
                                    </div>
                                    {!! Form::number('id_increament', @$max_code, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                    
                            @foreach ($lang as $lg)
                            <!-- Nama Field -->
                            <div class="form-group col-sm-10">
                                {!! Form::label('nama', 'Nama Kabupaten/Kota[' . $lg->code . ']:', ['class' => 'd-block']) !!}
                                {!! Form::text('nama[' . $lg->code . ']', $wilayah->getTranslation('nama', $lg->code) , ['class' => 'form-control']) !!}
                            </div>
                            @endforeach
                    
                            <!-- Alamat Kantor Field -->
                            <div class="form-group col-sm-10 col-lg-10">
                                {!! Form::label('alamat_kantor_pemerintahan', 'Alamat Kantor Pemerintahan:', ['class' => 'd-block']) !!}
                                {!! Form::textarea('alamat_kantor_pemerintahan', null, ['class' => 'form-control', 'rows' => 2]) !!}
                            </div>
                    
                            <!-- Alamat Kantor DPRD Field -->
                            <div class="form-group col-sm-10 col-lg-10">
                                {!! Form::label('alamat_kantor_dprd', 'Alamat Kantor DPRD:', ['class' => 'd-block']) !!}
                                {!! Form::textarea('alamat_kantor_dprd', null, ['class' => 'form-control', 'rows' => 2]) !!}
                            </div>
                    
                            <!-- Lattitude Field -->
                            <div class="form-group col-sm-12">
                                {!! Form::label('', 'Koordinat:', ['class' => 'd-block']) !!}
                                <div class="input-group mg-b-10">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text" id="basic-addon1">Latitude</span>
                                    </div>
                                    {!! Form::text('latitude', null, ['class' => 'form-control', 'aria-describedby' => 'basic-addon1']) !!}
                                    <div class="input-group-append">
                                      <span class="input-group-text" id="basic-addon2">Longitude</span>
                                    </div>
                                    {!! Form::text('longitude', null, ['class' => 'form-control', 'aria-describedby' => 'basic-addon2']) !!}
                                </div>
                            </div>
                    
                            <!-- Id Dataran Field -->
                            <div class="form-group col-sm-6">
                                {!! Form::label('id_dataran', 'Kategori Dataran:') !!}
                                {!! Form::select('id_dataran', $dataran, null, ['class' => 'form-control select2']) !!}
                            </div>

                            <!-- Memiliki data nilai Field -->
                            <div class="form-group col-sm-6">
                                {!! Form::label('has_data', 'Memiliki Data Nilai Bidang/Sektor:') !!}
                                <div class="custom-control custom-checkbox">
                                    {!! Form::checkbox('has_data', 1, @$wilayah->has_data == '1' ? true:false, ['class' => 'custom-control-input', 'id' => 'customCheck1']) !!}
                                    <label class="custom-control-label" for="customCheck1">Ada</label>
                                </div>
                            </div>   
                        </div>
                        <div class="col-sm-4">
                            <!-- Logo Field -->
                            <div class="form-group col-sm-12 col-lg-12">
                                {!! Form::label('peta_light_mode', 'Peta Light Mode:', ['class' => 'd-block']) !!}
                                {!! Form::file('peta_light_mode', [
                                    'class' => 'form-control dropify',
                                    'data-default-file' => @$wilayah->peta_light_mode ? env('APP_STORAGE').@$wilayah->peta_light_mode : '',
                                    'data-max-file-size' => '2M'
                                ]) !!}
                            </div>
                            <!-- Logo Field -->
                            <div class="form-group col-sm-12 col-lg-12">
                                {!! Form::label('peta_dark_mode', 'Peta Dark Mode:', ['class' => 'd-block']) !!}
                                {!! Form::file('peta_dark_mode', [
                                    'class' => 'form-control dropify',
                                    'data-default-file' => @$wilayah->peta_dark_mode ? env('APP_STORAGE').@$wilayah->peta_dark_mode : '',
                                    'data-max-file-size' => '2M',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="clearfix"></div>
                    <hr>
                    
                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{!! route('wilayahs.cities', @$province->id) !!}" class="btn btn-light">Cancel</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $('.dropify').dropify({
                messages: {
                    default: 'Drag and drop file here or click',
                    replace: 'Drag and drop file here or click to Replace',
                    remove: 'Remove',
                    error: 'Sorry, the file is too large'
                }
            });
        });

        $(document).on('click', '#kode', function(){
            if($("#box-input").hasClass('d-none')) $("#box-input").removeClass('d-none');
            else $("#box-input").addClass('d-none');
        })
    </script>
@endsection