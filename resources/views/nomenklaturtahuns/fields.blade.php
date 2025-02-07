    <!-- Tahun Field -->
    <div class="form-group col-sm-3">
        {!! Form::label('tahun', 'Tahun Data:') !!}
        @if (!empty($tahun))
            {!! Form::text('tahun', @$tahun, ['class' => 'form-control', 'disabled']) !!}
        @else
            {!! Form::text('tahun', null, [
                'class' => 'form-control',
                'placeholder' => 'YYYY',
                'id' => 'inputYear',
                'required',
            ]) !!}
        @endif
    </div>

    <div class="form-group col-sm-3">
        {!! Form::label('active', 'Tahun aktif:') !!}
        @if (!empty($active))
            {!! Form::select('active', ['active' => 'Tahun Aktif', 'inactive' => 'Tahun Tidak Aktif'], $active, [
                'class' => 'form-control select2',
                'placeholder' => 'Pilih status keaktifan tahun',
                'required',
            ]) !!}
        @else
            {!! Form::select('active', ['active' => 'Tahun Aktif', 'inactive' => 'Tahun Tidak Aktif'], 'active', [
                'class' => 'form-control select2',
                'placeholder' => 'Pilih status keaktifan tahun',
                'required',
            ]) !!}
        @endif
    </div>

    <!-- default Field -->

    <div class="form-group col-sm-3">
        {!! Form::label('default_year', 'Tahun default:') !!}
        @if (!empty($active))
            @if ($active == 'active')
                {!! Form::select('default_year', ['1' => 'Tahun Default', '0' => 'Tahun Tidak Default'], $default_year, [
                    'class' => 'form-control select2',
                    'placeholder' => 'Pilih tahun default',
                    'required',
                    $default_year == '1' ? 'disabled' : '',
                ]) !!}
            @else
                {!! Form::select('default_year', ['1' => 'Tahun Default', '0' => 'Tahun Tidak Default'], '0', [
                    'class' => 'form-control select2',
                    'placeholder' => 'Pilih tahun default',
                    'required',
                    'disabled',
                ]) !!}
            @endif
        @else
            {!! Form::select('default_year', ['1' => 'Tahun Default', '0' => 'Tahun Tidak Default'], '0', [
                'class' => 'form-control select2',
                'placeholder' => 'Pilih tahun default',
                'required',
            ]) !!}
        @endif
    </div>

    <div class="clearfix"></div>
    <hr>

    <!-- Submit Field -->
    <div class="form-group col-sm-12">
        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
        <a href="{!! route('nomenklaturtahuns.index') !!}" class="btn btn-light">Cancel</a>
    </div>

    @section('scripts')
        <script src="{{ asset('vendor/dashforge/lib/cleave.js/cleave.min.js') }}"></script>
        <script>
            $(function() {
                'use strict'

                var cleave = new Cleave('#inputYear', {
                    date: true,
                    datePattern: ['Y']
                });
            });

            $(document).ready(function() {
                $(".select2").select2();
            });

            $("#id_nomenklatur").select2({
                tags: true,
                tokenSeparators: [',', ' ']
            });
        </script>
    @endsection
