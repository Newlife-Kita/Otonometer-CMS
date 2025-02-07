@if (!empty(@$parent))
    <div class="col-sm-12">
        <h5>Childs Of {!! strtoupper(@$parent->kode) !!}.{!! @$parent->nama !!} </h5>
    </div>
@endif


<!-- Kode Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_increament', 'Kode:', ['class' => 'd-block']) !!}
    <div class="input-group mg-b-10">
        @if (!empty(@$parent))
            @if (!empty(@$parent->id_parent))
                <div class="input-group-prepend">
                    <span class="input-group-text tx-right">{{ $parent->kode }}</span>
                </div>
            @endif
        @endif
        {!! Form::hidden('segment', @$segment) !!}
        {!! Form::hidden('id_parent', @$parent->id) !!}
        {!! Form::number('id_increament', null, ['class' => 'form-control', 'required']) !!}

        <div class="input-group-prepend">
            <span class="input-group-text tx-right">{{ $parent->kode }}</span>
        </div>
    </div>

</div>

<!-- Nama Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nama', 'Initial/Singkatan:', ['class' => 'd-block']) !!}
    @foreach ($bahasa as $lg)
        @if ($loop->index > 0)
            <div class="mg-t-10"></div>
        @endif
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">{{ $lg->label }} ({{ $lg->code }})</span>
            </div>
            {!! Form::text(
                'nama[' . $lg->code . ']',
                !empty($bidang->nama) ? $bidang->getTranslation('nama', $lg->code) : null,
                ['class' => 'form-control', 'required'],
            ) !!}
        </div>
    @endforeach
</div>

<!-- Deskripsi Field -->
<div class="form-group col-sm-6">
    {!! Form::label('description', 'Deskripsi:', ['class' => 'd-block']) !!}
    @foreach ($bahasa as $lg)
        @if ($loop->index > 0)
            <div class="mg-t-10"></div>
        @endif
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">{{ $lg->label }} ({{ $lg->code }})</span>
            </div>
            {!! Form::text(
                'description[' . $lg->code . ']',
                !empty($bidang->description) ? $bidang->getTranslation('description', $lg->code) : null,
                ['class' => 'form-control'],
            ) !!}
        </div>
    @endforeach
</div>

<!-- Status Field -->
<div class="form-group col-sm-2">
    {!! Form::label('multi_select', 'Pilih Lebih dari satu:', ['class' => 'd-block']) !!}
    {!! Form::select('multi_select', ['y' => 'Ya', 'n' => 'Tidak'], null, [
        'class' => 'form-control select2',
    ]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-2">
    {!! Form::label('id_satuan', 'Unit:', ['class' => 'd-block']) !!}
    {!! Form::select('id_satuan', $satuan, null, ['class' => 'form-control select2', 'required']) !!}
</div>


<!-- Status Field -->
<div class="form-group col-sm-2">
    {!! Form::label('contain_data', 'Ketersediaan Data:', ['class' => 'd-block']) !!}
    {!! Form::select('contain_data', ['y' => 'Data Tersedia', 'n' => 'Data Tidak Tersedia'], null, [
        'class' => 'form-control select2',
        'required',
    ]) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-2">
    {!! Form::label('status', 'Status:', ['class' => 'd-block']) !!}
    {!! Form::select('status', ['tampil' => 'Tampilkan', 'sembunyikan' => 'Sembunyikan'], null, [
        'class' => 'form-control select2',
    ]) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-10">
    {!! Form::label('id_notes', 'Catatan :', ['class' => 'd-block']) !!}
    {!! Form::select('id_notes[]', $note, null, [
        'class' => 'form-control select2',
        'multiple' => 'multiple',
        'id' => 'id_notes',
    ]) !!}
</div>

<!-- Sumber Data Field -->
<div class="form-group col-sm-10">
    {!! Form::label('id_sumber', 'Sumber Data :', ['class' => 'd-block']) !!}
    {!! Form::select('id_sumber[]', $sumber, null, [
        'class' => 'form-control select2',
        'multiple' => 'multiple',
        'id' => 'id_sumber',
    ]) !!}
</div>

<div class="clearfix"></div>
<hr>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    @if (!empty(@$parent))
        @if (!empty(@$parent->id_parent))
            <a class="btn btn-sm btn-light" href="{!! route('sektor-bidang.' . strtolower($segment) . '.child_index', $parent->id) !!}"> Cancel </a>
        @else
            <a class="btn btn-sm btn-light" href="{!! route('sektor-bidang.' . strtolower($segment) . '.index') !!}"> Cancel </a>
        @endif
    @else
        <a class="btn btn-sm btn-light" href="{!! route('sektor-bidang.' . strtolower($segment) . '.index') !!}"> Cancel </a>
    @endif
</div>

@section('scripts')
    <!-- Relational Form table -->
    <script>
        $(document).ready(function() {
            $('.dropify').dropify({
                messages: {
                    default: 'Drag and drop file here or click',
                    replace: 'Drag and drop file here or click to Replace',
                    remove: 'Remove',
                    error: 'Sorry, the file is too large'
                }
            });

            $(".select2").select2();
            $("#id_notes").select2({
                tags: true,
                tokenSeparators: [',', ' ']
            });
            $("#id_sumber").select2({
                tags: true,
                tokenSeparators: [',', ' ']
            });
        });
    </script>
    <!-- End Relational Form table -->
@endsection
