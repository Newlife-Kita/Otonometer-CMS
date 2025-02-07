<!-- Id Wilayah Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_wilayah', 'Wilayah:') !!}
    {!! Form::select('id_wilayah', $masterwilayah, @$idWilayah, [
        'class' => 'form-control select2',
    ]) !!}
</div>

<!-- Tahun Field -->
<div class="form-group col-sm-2">
    {!! Form::label('tahun', 'Tahun:') !!}
    {!! Form::number('tahun', @$datawilayah->tahun ?? date('Y'), [
        'class' => 'form-control date',
    
        'min' => '1945',
        'max' => date('Y'),
    ]) !!}
</div>

<!-- Id Sektor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_sektor', 'PDRB Unggulan:') !!}
    {!! Form::select('id_sektor', $mastersektor, @$datawilayah->id_sektor, [
        'class' => 'form-control select2',
        'placeholder' => 'Pilih Sektor',
    ]) !!}
</div>

<!-- Nilai Sektor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nilai_sektor', 'Nilai PDRB:') !!}
    {!! Form::text('nilai_sektor', @$datawilayah->nilai_sektor, ['class' => 'form-control']) !!}
</div>

<!-- Ketinggian Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ketinggian', 'Ketinggian: ') !!}
    <div class="input-group mg-b-10">
        {!! Form::text('ketinggian', @$datawilayah->ketinggian, ['class' => 'form-control']) !!}
    </div>
</div>

<!-- Luas Wilayah Field -->
<div class="form-group col-sm-6">
    {!! Form::label('luas_wilayah', 'Luas Wilayah:') !!}
    <div class="input-group mg-b-10">
        {!! Form::text('luas_wilayah', @$datawilayah->luas_wilayah, ['class' => 'form-control']) !!}
        <div class="input-group-append">
            <span class="input-group-text" id="basic-addon2">Ribu/ Km<sup>2</sup></span>
        </div>
    </div>
</div>

<!-- Jumlah Penduduk Field -->
<div class="form-group col-sm-6">
    {!! Form::label('jumlah_penduduk', 'Jumlah Penduduk:') !!}
    <div class="input-group mg-b-10">
        {!! Form::text('jumlah_penduduk', @$datawilayah->jumlah_penduduk, ['class' => 'form-control']) !!}
        <div class="input-group-append">
            <span class="input-group-text" id="basic-addon2">Ribu Jiwa</span>
        </div>
    </div>
</div>


<div class="clearfix"></div>
<hr>

<!-- Submit Field -->


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
