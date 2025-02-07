@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pejabat Suku Dinas</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('dashforge-templates::common.errors')

            <h4 class="mg-b-10">Upload Data Pejabat Suku {{ @$sudin->nama_sudin }} {{ @$wilayah->nama }}</h4>

            <div class="align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div class="d-md-block">
                    {!! Form::open(['route' => ['sudins.upload_pejabat', 'id' => $id], 'files' => true]) !!}
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <a href="{!! route('sudins.download_2', $id) !!}" class="btn btn-success mg-t-30 btn-download"><i
                                    class="fa fa-download"></i> Download Template (Excel File)</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            {!! Form::label('File', 'Upload File Excel:') !!}
                            {!! Form::file('file', [
                                'class' => 'form-control dropify',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <hr>

                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Upload', ['class' => 'btn btn-primary btn-upload']) !!}
                        <a href="{!! route('pejabatwilayahs.index') !!}" class="btn btn-light">Cancel</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
@endsection

@section('scripts')
    <script>
        $(function() {
            $(".select2").select2();
            $('.dropify').dropify({
                messages: {
                    default: 'Drag and drop file here or click',
                    replace: 'Drag and drop file here or click to Replace',
                    remove: 'Remove',
                    error: 'Sorry, the file is too large'
                }
            });

            $(document).on('click', '.btn-upload', function() {
                let wilayah = $("#id_wilayah").val();

                if (parseInt(wilayah) < 1) {
                    Swal.fire({
                        text: 'Provinsi/Kab/Kota harus di pilih?',
                        icon: 'warning',
                        confirmButtonColor: '#0168fa',
                    })
                } else {
                    $("#frmsudin").submit();
                }
            });

            $(document).on('click', '.table-del', function(e) {
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
                        $("#table-form-" + id).submit();
                    }
                })
            });
        });
    </script>
@endsection
