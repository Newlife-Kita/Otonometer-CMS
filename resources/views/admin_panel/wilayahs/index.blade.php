@extends('layouts.app')

@section('contents')
    <div class="content">
        <div class="container">
            @include('dashforge-templates::common.errors')

            <h4 id="section1" class="mg-b-10">{{ strtoupper(@$wilayah->nama) }}</h4>

            {{-- <p class="mg-b-30">Please, fill all required fields before click save button.</p> --}}
            {!! Form::open(['id' => 'form_wilayah']) !!}
                <div class="row">
                    <div class="col-sm-5">
                        <div class="card align-items-center">
                            <img src="{{ getFileUrl(@$wilayah->peta_light_mode) }}" class="card-img-top wd-200" alt="Peta {{ @$wilayah->nama }}">
                            <div class="card-body">                            
                                <div class="form-group col-sm-12">
                                    {!! Form::label('nama', 'Wilayah/Daerah:', ['class' => 'd-block']) !!}
                                    {!! Form::text('nama', @$wilayah->nama, ['class' => 'form-control']) !!}
                                    {!! Form::hidden('parent_id', @$wilayah->id) !!}
                                </div>
                        
                                <!-- Alamat Kantor Field -->
                                <div class="form-group col-sm-12">
                                    {!! Form::label('alamat_kantor_pemerintahan', 'Alamat Kantor Pemerintahan:', ['class' => 'd-block']) !!}
                                    {!! Form::textarea('alamat_kantor_pemerintahan', @$wilayah->alamat_kantor_pemerintahan, ['class' => 'form-control', 'rows' => 2]) !!}
                                </div>
                        
                                <!-- Alamat Kantor DPRD Field -->
                                <div class="form-group col-sm-12">
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
                                    </div>
                                    <div class="input-group mg-b-10">
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
                    </div>
                    <div class="col-sm-2"></div>
                    <div class="col-sm-3">
                    </div>
                </div>

                <div class="clearfix"></div>
                <hr>
                        
                <!-- Submit Field -->
                <div class="form-group col-sm-12">
                    <button type="submit" class="btn btn-outline-primary rounded-pill"><i class="fas fa-save"></i> Simpan</button>
                </div>
            {!! Form::close() !!}
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
                    if(result.valid == true){
                        Swal.fire({
                            text: result.message,
                            icon: 'success',
                        })
                    }
                    else{
                        Swal.fire({
                            text: result.message,
                            icon: 'error',
                        })
                    }
                },
                error: function(e){
                    if(e.status == '403'){
                        Swal.fire({
                            text: !e.responseJSON ? e.statusText : e.responseJSON,
                            icon: 'error',
                        })
                    }
                }
            })
            return false;
        })
</script>
@endsection
