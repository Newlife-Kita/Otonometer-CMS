<div class="row">
    <div class="col-sm-6">
        @if (!empty($parent))
            <!-- Tipe Field -->
            <div class="form-group col-sm-6">
                {!! Form::label('parent', 'Provinsi:', ['class' => 'd-block']) !!}
                {!! Form::text('parent', @$parent->nama, ['class' => 'form-control', 'readonly']) !!}
                {!! Form::hidden('parent_id', @$parent->id) !!}
            </div>
            <!-- Tipe Field -->
            <div class="form-group col-sm-6">
                {!! Form::label('tipe', 'Kabupaten/Kota:', ['class' => 'd-block']) !!}
                {!! Form::select('tipe', ['kabupaten', 'kota'], null, ['class' => 'form-control select2']) !!}
            </div>

            <!-- Kode Field -->
            <div class="form-group col-sm-4">
                {!! Form::label('kode', 'Kode:', ['class' => 'd-block']) !!}
                <div class="input-group mg-b-10">
                    {!! Form::text('kode', @$wilayah->kode, ['class' => 'form-control', 'id' => 'kode', 'disabled']) !!}
                    <div class="input-group-append">
                        <button type="button" class="btn btn-xs btn-primary">Ubah Kode</button>
                    </div>
                </div>

                <div class="input-group mg-b-10">
                    <div class="input-group-prepend d-none" id="box-input">
                        <span class="input-group-text">{{ $parent->id_increament }}.</span>
                    </div>
                    {!! Form::number('id_increament', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        @else
            {!! Form::hidden('tipe', 'propinsi') !!}
        @endif

        @for($lang as $lg)
        <!-- Nama Field -->
        <div class="form-group col-sm-10">
            @if (!empty($parent))
                {!! Form::label('nama', 'Nama Kabupaten/Kota: [' . $lg->code . ']', ['class' => 'd-block']) !!}
            @else
                {!! Form::label('nama', 'Nama Provinsi: [' . $lg->code . ']', ['class' => 'd-block']) !!}
            @endif

           
            {!! Form::text('nama[' . $lg->code . ']', null, ['class' => 'form-control']) !!}        

     

        </div>
        @endfor

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


        <!-- Kodepos Field -->
        <div class="form-group col-sm-4">
            {!! Form::label('kodepos', 'Kodepos:', ['class' => 'd-block']) !!}
            @if (!empty($parent))
                <div class="input-group mg-b-10">
                    {!! Form::text('kodepos', null, ['class' => 'form-control', 'maxlength' => 3]) !!}
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">XX</span>
                    </div>
                </div>
            @else
                <div class="input-group mg-b-10">
                    {!! Form::text('kodepos', null, ['class' => 'form-control', 'maxlength' => 1]) !!}
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">XXXX</span>
                    </div>
                </div>
            @endif
            <span class="d-block mg-t-10 tx-primary tx-10">Format Harus Angka</span>
        </div>

        <!-- Koordinat Field -->
        {{-- <div class="form-group col-sm-10">
            {!! Form::label('koordinat', 'Koordinat Lokasi:', ['class' => 'd-block']) !!}
            {!! Form::text('koordinat', null, ['class' => 'form-control']) !!}
        </div> --}}

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

        <!-- Can Get Data Kota -->
        <div class="form-group col-sm-6">
            {!! Form::label('id_dataran', 'Kategori Dataran:') !!}
            {!! Form::select('id_dataran', $dataran, null, ['class' => 'form-control select2']) !!}
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
                'data-default-file' => @$wilayah->peta_light_mode ? getFileUrl(@$wilayah->peta_light_mode) : '',
                'data-max-file-size' => '2M',
            ]) !!}
        </div>
        <!-- Logo Field -->
        <div class="form-group col-sm-12 col-lg-12">
            {!! Form::label('peta_dark_mode', 'Peta Dark Mode:', ['class' => 'd-block']) !!}
            {!! Form::file('peta_dark_mode', [
                'class' => 'form-control dropify',
                'data-default-file' => @$wilayah->peta_dark_mode ? getFileUrl(@$wilayah->peta_dark_mode) : '',
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
    @if (!empty($parent))
        <a href="{!! route('wilayahs.cities', @$parent->id) !!}" class="btn btn-light">Cancel</a>
    @else
        <a href="{!! route('wilayahs.index') !!}" class="btn btn-light">Cancel</a>
    @endif
</div>


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
