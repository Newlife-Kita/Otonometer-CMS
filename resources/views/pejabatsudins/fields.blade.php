<div class="row">
    <div class="col-sm-6">
        <!-- Id Suku Dinas Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('id_suku_dinas', 'Suku Dinas:') !!}
            {!! Form::text('label_suku_dinas', @$sukudinas->nama_sudin, ['class' => 'form-control', 'disabled']) !!}
            {!! Form::hidden('id_suku_dinas', @$sukudinas->id) !!}
        </div>

        <!-- Nama Lengkap Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('nama_lengkap', 'Nama Lengkap:', ['class' => 'd-block']) !!}
            {!! Form::text('nama_lengkap', @$pejabatsudin->nama_lengkap, ['class' => 'form-control', 'required']) !!}
        </div>

        <!-- Id Jabatan Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('id_jabatan', 'Jabatan:') !!}
            {!! Form::select('id_jabatan', $masterjabatan, @$pejabatsudin->id_jabatan, [
                'class' => 'form-control select2',
                'required',
                'placeholder' => 'Pilih Jabatan',
            ]) !!}
        </div>

        <!-- Tahun Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('tahun_lantik', 'Tahun Lantik:') !!}
            {!! Form::number('tahun_lantik', $pejabatsudin->tahun_lantik ?? date('Y'), [
                'class' => 'form-control date',
                'required',
                'min' => '1945',
                'max' => date('Y'),
                'required',
            ]) !!}
        </div>

        <!-- Periode Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('tahun_akhir', 'Tahun Akhir:') !!}
            {!! Form::number('tahun_akhir', $pejabatsudin->tahun_akhir ?? date('Y'), [
                'class' => 'form-control date',
                'required',
                'min' => '1945',
                'required',
            ]) !!}
        </div>
    </div>
    <!-- Foto Field -->
    <div class="form-group col-xs-12 col-sm-3">
        {!! Form::label('foto', 'Foto:', ['class' => 'd-block']) !!}
        {!! Form::file('foto', [
            'class' => 'form-control dropify',
            'data-default-file' => @$pejabatsudin->foto ? getFileUrl(@$pejabatsudin->foto) : '',
            'data-max-file-size' => '2M',
            @$pejabatsudin->foto ? '' : 'required',
        ]) !!}
    </div>
</div>

<div class="clearfix"></div>
<hr>


@section('scripts')
    <!-- Relational Form table -->
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
        });
    </script>
    <!-- End Relational Form table -->
@endsection
