@extends('layouts.app')

@section('contents')
    <div class="content">
        <div class="container">
            @include('dashforge-templates::common.errors')

            <h4 id="section1" class="mg-b-10">Suku Dinas {{ $wilayah->nama }}</h4>
            <div style="margin-right: -15px;margin-left: -15px;">
                <div data-label="Edit" class="df-example demo-forms services-forms">
                    {!! Form::open(['route' => 'panel.sudins.store']) !!}
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="card bd bd-light">
                                <div class="card-header bg-light"><h6>Informasi Umum</h6></div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group col-sm-12">
                                                {!! Form::file('logo', [
                                                    'class' => 'form-control dropify',
                                                    'data-default-file' => '',
                                                    'data-max-file-size' => '2M',
                                                ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            @foreach ($bahasa as $lg)
                                                @if ($loop->index > 0)
                                                    <div class="mg-t-10"></div>
                                                @endif
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">{{ $lg->label }}</span>
                                                    </div>
                                                    {!! Form::text(
                                                        'nama_sudin[' . $lg->code . ']', null,
                                                        ['class' => 'form-control'],
                                                    ) !!}
                                                </div>
                                            @endforeach                                
                                            {!! Form::hidden('id_wilayah', @$wilayah->id) !!}
                                            
                                            <div class="input-group mg-t-20">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                </div>
                                                {!! Form::text('alamat', null, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 

                            <div class="card bd bd-light mg-t-10">
                                <div class="card-header bg-light"><h6>PIC</h6></div>
                                <div class="card-body">
                                    <!-- Nama Pic Field -->
                                    <div class="form-group col-sm-12">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            </div>
                                            {!! Form::text('nama_pic', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>

                                    <!-- Telp Pic Field -->
                                    <div class="form-group col-sm-12">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            </div>
                                            <div class="input-group-append">
                                                <span class="input-group-text">+62</span>
                                            </div>
                                            {!! Form::text('telp_pic', null, ['class' => 'form-control', 'id' => 'telp_pic']) !!}
                                        </div>
                                    </div>

                                    <!-- Email Pic Field -->
                                    <div class="form-group col-sm-12">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            {!! Form::email('email_pic', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>                             
                        </div>
                        
                    </div>

                    <div class="clearfix"></div>
                    <hr>

                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        <button type="submit" class="btn btn-outline-primary rounded-pill"><i class="fas fa-save"></i> Simpan</button>
                        <a href="{!! route('panel.sudins.index') !!}" class="btn btn-outline-light rounded-pill"><i class="fas fa-ban"></i> Batal</a>
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
    </script>
    <!-- End Relational Form table -->
@endsection