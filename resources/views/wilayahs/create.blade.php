@extends('layouts.app')

@section('contents')
    <div class="content">
        <div class="container">
            @include('dashforge-templates::common.errors')

            <h4 id="section1" class="mg-b-10">Tambah Data Provinsi</h4>

            <p class="mg-b-30">Please, fill all required fields before click save button.</p>

            <div style="margin-right: -15px;margin-left: -15px;">
                <div data-label="Create" class="df-example demo-forms services-forms">
                    {!! Form::open(['route' => 'wilayahs.store']) !!}
                    <div class="row">
                        <div class="col-sm-6">
                            {!! Form::hidden('tipe', 'propinsi') !!}
                            {!! Form::hidden('kodepos', null) !!}

                            <!-- Kode Field -->
                            <div class="form-group col-sm-3">
                                {!! Form::label('kode', 'Kode:', ['class' => 'd-block']) !!}

                                <div class="input-group mg-b-10">
                                    {!! Form::number('id_increament', @$max_code, ['class' => 'form-control']) !!}
                                    <div class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </div>
                                </div>
                            </div>

                            @foreach ($lang as $lg)
                                <!-- Nama Field -->
                                <div class="form-group col-sm-10">
                                    {!! Form::label('nama', 'Nama Provinsi [' . $lg->code . ']:', ['class' => 'd-block']) !!}
                                    {!! Form::text('nama[' . $lg->code . ']', null, ['class' => 'form-control']) !!}
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
                                    {!! Form::checkbox('has_data', 1, false, ['class' => 'custom-control-input', 'id' => 'customCheck1']) !!}
                                    <label class="custom-control-label" for="customCheck1">Ada</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <!-- Logo Field -->
                            {{-- <div class="form-group col-sm-12 col-lg-12">
                                {!! Form::label('logo', 'Logo Daerah/Wilayah:', ['class' => 'd-block']) !!}
                                {!! Form::file('logo', [
                                    'class' => 'form-control dropify',
                                    'data-default-file' => @$wilayah->logo ? asset(@$wilayah->logo) : '',
                                    'data-max-file-size' => '2M',
                                    @$wilayah->logo ? '' : 'required',
                                ]) !!}
                            </div> --}}
                            <!-- Logo Field -->
                            <div class="form-group col-sm-12 col-lg-12">
                                {!! Form::label('peta_light_mode', 'Peta Light Mode:', ['class' => 'd-block']) !!}
                                {!! Form::file('peta_light_mode', [
                                    'class' => 'form-control dropify',
                                    'data-default-file' => @$wilayah->peta_light_mode ? asset(@$wilayah->peta_light_mode) : '',
                                    'data-max-file-size' => '2M',
                                ]) !!}
                            </div>
                            <!-- Logo Field -->
                            <div class="form-group col-sm-12 col-lg-12">
                                {!! Form::label('peta_dark_mode', 'Peta Dark Mode:', ['class' => 'd-block']) !!}
                                {!! Form::file('peta_dark_mode', [
                                    'class' => 'form-control dropify',
                                    'data-default-file' => @$wilayah->peta_dark_mode ? asset(@$wilayah->peta_dark_mode) : '',
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
                        <a href="{!! route('wilayahs.index') !!}" class="btn btn-light">Cancel</a>
                    </div>



                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('scripts')
    <!-- Relational Form table -->
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

        $(document).on('click', '#kode', function() {
            if ($("#box-input").hasClass('d-none')) $("#box-input").removeClass('d-none');
            else $("#box-input").addClass('d-none');
        })
    </script>
    <!-- End Relational Form table -->
@endsection
