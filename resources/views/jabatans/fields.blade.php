<!-- Nama Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nama', 'Nama:', ['class' => 'd-block']) !!}
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
                !empty($jabatan->nama) ? $jabatan->getTranslation('nama', $lg->code) : null,
                ['class' => 'form-control', 'required'],
            ) !!}
        </div>
    @endforeach
</div>

<!-- Tipe Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tipe', 'Tipe:', ['class' => 'd-block']) !!}
    {!! Form::select('tipe', ['' => 'Pilih Tipe', 'pemda' => 'PEMDA', 'dprd' => 'DPRD', 'dinas' => 'SUDIN'], null, [
        'class' => 'form-control select2',
        'id' => 'tipe',
    ]) !!}
</div>

<!-- Tipe Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tipe_wilayah', 'Tipe Daerah:', ['class' => 'd-block']) !!}
    {!! Form::select(
        'tipe_wilayah',
        ['' => 'Pilih Tipe', 'propinsi' => 'Propinsi', 'kabupaten' => 'Kabupaten', 'kota' => 'Kota'],
        null,
        ['class' => 'form-control select2', 'id' => 'tipe_wilayah'],
    ) !!}
</div>

<!-- Urutan Field -->
<div class="form-group col-sm-6">
    {!! Form::label('urutan', 'Urutan/Posisi:') !!}
    {!! Form::select('urutan', $urutan, null, ['class' => 'form-control select2', 'id' => 'urutan']) !!}
    <span class="tx-info tx-10 tx-bold">Pilih Tipe dan Tipe Wilayah terlebih dahulu untuk bisa memilih
        posisi/urutan</span>
</div>

<div class="clearfix"></div>
<hr>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('jabatans.index') !!}" class="btn btn-light">Cancel</a>
</div>

@section('styles')
    <style>
        .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px);
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 5px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 34px;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".select2").select2();

            @if (!empty($jabatan->urutan))
                $("#urutan option").each(function(index) {
                    if (index == '{{ intval($jabatan->urutan) }}') {
                        $(this).prop('selected', 'selected');
                    }
                });
                $("#urutan").select2();
            @endif
        });

        $(function() {
            $('#tipe').on('select2:select', function(e) {

                let wilayah = $('#tipe_wilayah').val();
                var data = e.params.data;
                $.ajax({
                    url: "{{ route('jabatans.show', 1) }}",
                    data: {
                        type_wilayah: wilayah,
                        type: data.id,
                        id: '{{ @$jabatan->id }}'
                    },
                    success: function(result) {
                        let html = '<option value="-1">Urutan Pertama</option>';
                        $.each(result.items, function(index, value) {
                            html += '<option value="' + value.id + '"> Setelah  ' +
                                value.nama + '</option>';
                        });
                        $("#urutan").html(html).select2();
                    }
                });
            });

            $('#tipe_wilayah').on('select2:select', function(e) {

                let tipe = $('#tipe').val();
                var data = e.params.data;
                $.ajax({
                    url: "{{ route('jabatans.show', 1) }}",
                    data: {
                        type_wilayah: data.id,
                        type: tipe,
                        id: '{{ @$jabatan->id }}'
                    },
                    success: function(result) {
                        let html = '<option value="-1">Urutan Pertama</option>';
                        $.each(result.items, function(index, value) {
                            html += '<option value="' + value.id + '"> Setelah  ' +
                                value.nama + '</option>';
                        });
                        $("#urutan").html(html).select2();
                    }
                });
            });
        });
    </script>
@endsection
