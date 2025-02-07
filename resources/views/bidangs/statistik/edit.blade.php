@extends('layouts.app')

@section('contents')
    <div class="content">
        <div class="container">
            @include('dashforge-templates::common.errors')

            <h4 id="section1" class="mg-b-10">Sektor/Bidang Statistik</h4>

            <p class="mg-b-30">Please, fill all required fields before click save button.</p>

            <div style="margin-right: -15px;margin-left: -15px;">
                <div data-label="Create" class="df-example demo-forms services-forms">
                    @if (!empty(@$parent))
                        <div class="col-sm-6">
                            <h5>Anakan Dari</h5>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text tx-bold wd-150">Kode</span>
                                </div>
                                {!! Form::text('parent_kode', strtoupper(@$parent->kode), ['class' => 'form-control tx-bold', 'disabled']) !!}
                            </div>
                            <div class="input-group mg-t-10">
                                <div class="input-group-prepend">
                                    <span class="input-group-text tx-bold">Inisial/Singkatan/Nama</span>
                                </div>
                                {!! Form::text('parent_nama', strtoupper(@$parent->nama), ['class' => 'form-control tx-bold', 'disabled']) !!}
                            </div>
                        </div>
                    @endif

                    <hr class="pd-b-10">
                </div>

                {!! Form::model($bidang, ['route' => ['sektor-bidang.statistik.update', $bidang->id], 'method' => 'patch']) !!}
                <!-- Kode Field -->
                <div class="form-group col-sm-3">
                    {!! Form::label('kode', 'Kode:', ['class' => 'd-block']) !!}
                    <div class="input-group mg-b-10">
                        {!! Form::hidden('id_parent', @$parent->id) !!}
                        {!! Form::text('kode', null, ['class' => 'form-control', 'id' => 'kode', 'required']) !!}
                    </div>

                </div>

                <!-- Nama Field -->
                <div class="form-group col-sm-6">
                    {!! Form::label('nama', 'Initial/Singkatan/Label:', ['class' => 'd-block']) !!}
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

                <!-- Flaging Field -->
                <div class="form-group col-sm-4">
                    {!! Form::label('flagging', 'Flaging Kota:', ['class' => 'd-block']) !!}
                    {!! Form::select(
                        'flagging',
                        ['all' => 'Propinsi & Kota', 'province' => 'Hanya Propinsi', 'city' => 'Hanya Kota'],
                        $bidang->flagging,
                        [
                            'class' => 'form-control select2',
                        ],
                    ) !!}
                </div>


                <!-- Status Field -->
                <div class="form-group col-sm-4">
                    {!! Form::label('id_satuan', 'Unit:', ['class' => 'd-block']) !!}
                    {!! Form::select('id_satuan', $satuan, $bidang->id_satuan, ['class' => 'form-control select2', 'required']) !!}
                </div>

                <!-- Status Field -->
                <div class="form-group col-sm-4">
                    {!! Form::label('root_selection', 'Sektor Utama:', ['class' => 'd-block']) !!}
                    <div class="d-flex">
                        {!! Form::select('root_selection', ['y' => 'Ya', 'n' => 'Tidak'], $bidang->root_selection, [
                            'class' => 'form-control select2',
                            'required',
                        ]) !!}
                        <button type="button" class="btn btn-light btn-xs btn-icon" data-container="body"
                            data-toggle="popover" data-placement="right"
                            data-content="Opsi ini menentukan apakah suatu sektor adalah sektor yang menjadi ROOT atau ACUAN dari sektor dibawahnya. Opsi menetukan bagaimana nilai persentase dikalkulasi pada modul UTAK-ATIK dan BERKACA."><i
                                class="fa fa-question-circle"></i></button>
                    </div>
                </div>

                <!-- Status Field -->
                <div class="form-group col-sm-4">
                    {!! Form::label('contain_data', 'Ketersediaan Data:', ['class' => 'd-block']) !!}
                    <div class="d-flex">
                        {!! Form::select('contain_data', ['y' => 'Data Tersedia', 'n' => 'Data Tidak Tersedia'], $bidang->contain_data, [
                            'class' => 'form-control select2',
                            'required',
                        ]) !!}
                        <button type="button" class="btn btn-light btn-xs btn-icon" data-container="body"
                            data-toggle="popover" data-placement="right"
                            data-content="Opsi ini menentukan apakah suatu sektor memiliki nilai untuk ditampilkan atau tidak. Jika suatu sektor tidak memiliki nilai maka sektor itu tidak akan memicu munculnya nilai ketika dipilih pada modul JELAJAH dan tidak akan memiliki anakan SEMUA pada modul UTAK-ATIK dan BERKACA."><i
                                class="fa fa-question-circle"></i></button>
                    </div>
                </div>

                <!-- Status Field -->
                <div class="form-group col-sm-4">
                    {!! Form::label('contain_alert', 'Picu Pop-Up:', ['class' => 'd-block']) !!}
                    <div class="d-flex">
                        {!! Form::select('contain_alert', ['y' => 'Ya', 'n' => 'Tidak'], $bidang->contain_alert, [
                            'class' => 'form-control select2',
                            'required',
                        ]) !!}
                        <button type="button" class="btn btn-light btn-xs btn-icon" data-container="body"
                            data-toggle="popover" data-placement="right"
                            data-content="Opsi ini menentukan apakah suatu sektor akan memicu munculnya pop up ketika dipilih oleh user."><i
                                class="fa fa-question-circle"></i></button>
                    </div>
                </div>

                <!-- Alamat Kantor DPRD Field -->
                <div class="form-group col-sm-10 col-lg-10">
                    {!! Form::label('content_alert', 'Teks Pop-Up:', ['class' => 'd-block']) !!}
                    {!! Form::textarea('content_alert', $bidang->content_alert, ['class' => 'form-control', 'rows' => 3]) !!}
                </div>


                <!-- Status Field -->
                <div class="form-group col-sm-4">
                    {!! Form::label('summable', 'Dapat Ditotal:', ['class' => 'd-block']) !!}
                    <div class="d-flex">
                        {!! Form::select('summable', ['y' => 'Ya dapat ditotal', 'n' => 'Tidak Dapat Total'], $bidang->summable, [
                            'class' => 'form-control select2',
                            'required',
                        ]) !!}
                        <button type="button" class="btn btn-light btn-xs btn-icon" data-container="body"
                            data-toggle="popover" data-placement="right"
                            data-content="Opsi ini menentukan apakah suatu sektor atau bidang memiliki opsi SEMUA."><i
                                class="fa fa-question-circle"></i></button>
                    </div>
                </div>

                <!-- Status Field -->
                <div class="form-group col-sm-3">
                    {!! Form::label('status', 'Status:', ['class' => 'd-block']) !!}
                    {!! Form::select('status', ['tampil' => 'Tampilkan', 'sembunyikan' => 'Sembunyikan'], $bidang->status, [
                        'class' => 'form-control select2',
                        'required',
                    ]) !!}
                </div>

                <!-- Notes Field -->
                <div class="form-group col-sm-10">
                    {!! Form::label('id_notes', 'Catatan :', ['class' => 'd-block']) !!}
                    {!! Form::select('id_notes[]', $note, $bidang->id_notes, [
                        'class' => 'form-control select2',
                        'multiple' => 'multiple',
                        'id' => 'id_notes',
                    ]) !!}
                </div>

                <!-- Sumber Data Field -->
                <div class="form-group col-sm-10">
                    {!! Form::label('id_sumber', 'Sumber Data :', ['class' => 'd-block']) !!}
                    {!! Form::select('id_sumber[]', $sumber, $bidang->id_sumber, [
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
                    <a class="btn btn-sm btn-light" href="{!! route('sektor-bidang.statistik.index') !!}"> Cancel </a>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    </div>
    <!-- /.content -->
@endsection

@section('scripts')
    <script src="{{ asset('vendor/dashforge/lib/cleave.js/cleave.min.js') }}"></script>
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

            var cleaveI = new Cleave('#kode', {
                delimiters: ['.', '.', '.', '.', '.'],
                blocks: [2, 2, 2, 2, 2, 2]
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

            let total_checkbox = $(".check-row").length;
            let checked_box = $(".check-row:checked").length;
            if (total_checkbox == checked_box) $("#checkAll").prop('checked', true);
            else $("#checkAll").prop('checked', false);

            $(document).on('click', '#checkAll', function() {
                if ($(this).prop('checked') == true) $(".check-row").prop('checked', true);
                else $(".check-row").prop('checked', false);
            })

            $(document).on('click', '.check-row', function() {
                let total = $(".check-row").length;
                let checked = $(".check-row:checked").length;
                if (checked == total) $("#checkAll").prop('checked', true);
                else $("#checkAll").prop('checked', false);
            })

            // Define the event handler for selectField
            $('#contain_alert').change(function() {
                var selectedValue = $(this).val();
                var inputField = $('#content_alert');

                if (selectedValue === 'y') {
                    inputField.prop('required', true);
                } else if (selectedValue === 'n') {
                    inputField.prop('required', false);
                }
            });

            // Trigger the change event manually on page load
            $('#contain_alert').trigger('change');
            $('[data-toggle="popover"]').popover();
        });
    </script>
@endsection
