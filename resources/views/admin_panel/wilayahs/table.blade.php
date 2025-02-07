@extends('layouts.app')

@section('contents')
    <div class="content">
        <div class="container">
            @include('dashforge-templates::common.errors')

            <h4 id="section1" class="mg-b-10">Kabupaten/Kota</h4>

            <p class="mg-b-30">Please, fill all required fields before click save button.</p>

            <div style="margin-right: -15px;margin-left: -15px;">
                <div data-label="Edit" class="df-example demo-forms services-forms">
                    {!! Form::open(['id' => 'form_wilayah']) !!}
                    <div class="row">
                        <div class="col-sm-6">                    
                            <!-- Nama Field -->
                            <div class="form-group col-sm-10">
                                {!! Form::label('nama', 'Nama Kabupaten/Kota:', ['class' => 'd-block']) !!}
                                {!! Form::text('nama', @$wilayah->nama, ['class' => 'form-control']) !!}
                                {!! Form::hidden('parent_id', @$wilayah->id) !!}
                            </div>
                    
                            <!-- Alamat Kantor Field -->
                            <div class="form-group col-sm-10 col-lg-10">
                                {!! Form::label('alamat_kantor_pemerintahan', 'Alamat Kantor Pemerintahan:', ['class' => 'd-block']) !!}
                                {!! Form::textarea('alamat_kantor_pemerintahan', @$wilayah->alamat_kantor_pemerintahan, ['class' => 'form-control', 'rows' => 2]) !!}
                            </div>
                    
                            <!-- Alamat Kantor DPRD Field -->
                            <div class="form-group col-sm-10 col-lg-10">
                                {!! Form::label('alamat_kantor_dprd', 'Alamat Kantor DPRD:', ['class' => 'd-block']) !!}
                                {!! Form::textarea('alamat_kantor_dprd', @$wilayah->alamat_kantor_dprd, ['class' => 'form-control', 'rows' => 2]) !!}
                            </div>
                    
                            <!-- Lattitude Field -->
                            <div class="form-group col-sm-12">
                                {!! Form::label('', 'Koordinat:', ['class' => 'd-block']) !!}
                                <div class="input-group mg-b-10">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text" id="basic-addon1">Latitude</span>
                                    </div>
                                    {!! Form::text('latitude', @$wilayah->latitude, ['class' => 'form-control', 'aria-describedby' => 'basic-addon1']) !!}
                                    <div class="input-group-append">
                                      <span class="input-group-text" id="basic-addon2">Longitude</span>
                                    </div>
                                    {!! Form::text('longitude', @$wilayah->longitude, ['class' => 'form-control', 'aria-describedby' => 'basic-addon2']) !!}
                                </div>
                            </div>
                    
                            <!-- Id Dataran Field -->
                            <div class="form-group col-sm-6">
                                {!! Form::label('id_dataran', 'Kategori Dataran:') !!}
                                {!! Form::select('id_dataran', $dataran, @$wilayah->id_dataran, ['class' => 'form-control select2']) !!}
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="clearfix"></div>
                    <hr>
                    
                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
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
        $(function () {        
            $('.select2').select2();
        });

        $(document).on('submit', '#form_wilayah', function(){
            $.ajax({
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('panel.wilayahs.update') }}",
                data: $('#form_wilayah').serialize(),
                success: function(result) {
                    Swal.fire({
                        text: 'Catatan berhasil disimpan',
                        icon: 'success',
                    })
                }
            })
            return false;
        })
    </script>
@endsection