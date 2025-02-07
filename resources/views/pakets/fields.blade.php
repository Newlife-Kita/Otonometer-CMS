<div class="row">
    <div class="col-sm-6">
        <!-- Nama Paket Field -->
        <div class="form-group col-sm-12 col-lg-12">
            {!! Form::label('nama_paket', 'Nama Paket:', ['class' => 'd-block']) !!}
            @foreach ($bahasa as $lg)
                @if ($loop->index > 0)
                    <div class="mg-t-10"></div>
                @endif
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{ $lg->label }} ({{ $lg->code }})</span>
                    </div>
                    {!! Form::text(
                        'nama_paket[' . $lg->code . ']',
                        !empty($paket->nama_paket) ? $paket->getTranslation('nama_paket', $lg->code) : null,
                        ['class' => 'form-control', 'required'],
                    ) !!}
                </div>
            @endforeach
        </div>

        <!-- Biaya Field -->
        <div class="form-group col-sm-6">
            {!! Form::label('biaya', 'Biaya:') !!}
            <div class="input-group">
                {!! Form::number('biaya', null, ['class' => 'form-control', 'id' => 'biaya']) !!}
                <div class="input-group-append">
                    <span class="input-group-text" id="biaya-amount">0</span>
                </div>
            </div>
        </div>

        <!-- Periode Field -->
        <div class="form-group col-sm-6">
            {!! Form::label('periode', 'Periode:') !!}
            <div class="input-group">
                {!! Form::number('periode', null, ['class' => 'form-control']) !!}
                <div class="input-group-append">
                    {!! Form::select(
                        'periode_label',
                        ['day' => 'Hari', 'week' => 'Minggu', 'month' => 'Bulan', 'year' => 'Tahun'],
                        null,
                        ['class' => 'form-control select2'],
                    ) !!}
                </div>
            </div>
        </div>

        <!-- Total Download Field -->
        <div class="form-group col-sm-6">
            {!! Form::label('total_download', 'Jumlah Maksimal Unduhan:') !!}
            <div class="input-group">
                {!! Form::number('total_download', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <!-- Total Save Field -->
        <div class="form-group col-sm-6">
            {!! Form::label('total_save', 'Jumlah Maksimal Halaman Tersimpan:') !!}
            <div class="input-group">
                {!! Form::number('total_save', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <!-- Total Collection Field -->
        <div class="form-group col-sm-6">
            {!! Form::label('total_collection', 'Jumlah Maksimal Koleksi:') !!}
            <div class="input-group">
                {!! Form::number('total_collection', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <!-- Total Save Per Collection Field -->
        <div class="form-group col-sm-6">
            {!! Form::label('total_save_per_collection', 'Jumlah Maksimal Tersimpan dalam Satu Koleksi:') !!}
            <div class="input-group">
                {!! Form::number('total_save_per_collection', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <!-- Status Field -->
        <div class="form-group col-sm-4">
            {!! Form::label('status', 'Status:', ['class' => 'd-block']) !!}
            {!! Form::select('status', ['tampil' => 'Tampilkan', 'sembunyikan' => 'Sembunyikan'], null, [
                'class' => 'form-control select2',
            ]) !!}
        </div>
    </div>
    <div class="col-sm-4">
        <!-- Icon Field -->
        <div class="form-group col-sm-12 col-lg-12">
            {!! Form::label('icon', 'Icon:', ['class' => 'd-block']) !!}
            {!! Form::file('icon', [
                'class' => 'form-control dropify',
                'data-default-file' => @$paket->icon ? asset(@$paket->icon) : '',
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
    <a href="{!! route('pakets.index') !!}" class="btn btn-light">Cancel</a>
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

        .input-group-append {
            width: 30%;
        }
    </style>
@endsection

@section('scripts')
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

        $(document).on('focusout', '#biaya', function() {
            let amount = $(this).val();
            $("#biaya-amount").html(formatRupiah(amount, 0));
        });

        function formatRupiah(amount, decimalCount = 2, decimal = ".", thousands = ",") {
            try {
                decimalCount = Math.abs(decimalCount);
                decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

                const negativeSign = amount < 0 ? "-" : "";
                let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
                let j = (i.length > 3) ? i.length % 3 : 0;

                return negativeSign +
                    (j ? i.substr(0, j) + thousands : '') +
                    i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) +
                    (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
            } catch (e) {
                console.log(e)
            }
        };
    </script>
@endsection
