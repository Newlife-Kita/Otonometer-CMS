@extends('layouts.app')

@section('contents')
    <div class="content">
        <div class="container">
            @include('dashforge-templates::common.errors')

            <h4 id="section1" class="mg-b-10">Sektor/Bidang Nomenklatur {{ @$tahun }}</h4>

            <p class="mg-b-30">Please, fill all required fields before click save button.</p>

            <div style="margin-right: -15px;margin-left: -15px;">
                <div class="df-example demo-forms services-forms">
                    {!! Form::open(['route' => 'nomenklaturs.savechild']) !!}                        
                    <div class="col-sm-12 mg-b-10">
                        @if(@$parent->level !== @$sektor['level'])
                            <h5 class="tx-dark">Childs Of <span class="head-code-label tx-bold">{!! strtoupper(@$parent->kode) !!}. {!! @$parent->nama !!}</span> </h5>
                        @endif
                    </div>

                    <!-- Kode Field -->
                    <div class="form-group col-sm-3">
                        {!! Form::label('kode', 'Kode:', ['class' => 'd-block']) !!}
                        <div class="input-group mg-b-10">
                            {!! Form::hidden('tahun', @$tahun) !!}
                            {!! Form::hidden('id_parent', @$parent->id) !!}
                            {!! Form::text('kode', @$kode_str, ['class' => 'form-control','id' => 'kode', 'required']) !!}
                        </div>
                    
                    </div>
                    
                    <!-- Nama Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('nama', 'Initial/Singkatan:', ['class' => 'd-block']) !!}
                        @foreach($bahasa as $lg)
                            @if($loop->index > 0)
                                <div class="mg-t-10"></div>
                            @endif
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ $lg->label }} ({{ $lg->code}})</span>
                                </div>
                                {!! Form::text('nama['.$lg->code.']', (!empty($nomenklatur->nama) ? $nomenklatur->getTranslation('nama', $lg->code) : null), ['class' => 'form-control', 'id' => 'nama_'.$lg->code, 'required']) !!}
                            </div> 
                        @endforeach
                    </div>

                    <!-- Deskripsi Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('description', 'Deskripsi:', ['class' => 'd-block']) !!}
                        @foreach($bahasa as $lg)
                            @if($loop->index > 0)
                                <div class="mg-t-10"></div>
                            @endif
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ $lg->label }} ({{ $lg->code}})</span>
                                </div>
                                {!! Form::text('description['.$lg->code.']', (!empty($nomenklatur->description) ? $nomenklatur->getTranslation('description', $lg->code) : null), ['class' => 'form-control', 'id' => 'description_'.$lg->code,]) !!}
                            </div> 
                        @endforeach
                    </div>

                    <!-- Status Field -->
                    <div class="form-group col-sm-2">
                    {!! Form::label('id_satuan', 'Unit:', ['class' => 'd-block']) !!}
                    {!! Form::select('id_satuan', $satuan, null, ['class' => 'form-control select2','id' => 'id_satuan',
                    'required']) !!}
                    </div>

                    <!-- Notes Field -->
                    <div class="form-group col-sm-10">
                        {!! Form::label('id_notes', 'Catatan :', ['class' => 'd-block']) !!}
                        {!! Form::select('id_notes[]', $note, null, [
                            'class' => 'form-control select2',
                            'multiple' => 'multiple',
                            'id' => 'id_notes'
                        ]) !!}
                    </div>

                    <!-- Sumber Data Field -->
                    <div class="form-group col-sm-10">
                        {!! Form::label('id_sumber', 'Sumber Data :', ['class' => 'd-block']) !!}
                        {!! Form::select('id_sumber[]', $sumber, null, [
                            'class' => 'form-control select2',
                            'multiple' => 'multiple',
                            'id' => 'id_sumber'
                        ]) !!}
                    </div>

                    <!-- Status Field -->
                    <div class="form-group col-sm-2">
                    {!! Form::label('status', 'Status:', ['class' => 'd-block']) !!}
                    {!! Form::select('status', ['tampil' => 'Tampilkan','sembunyikan' => 'Sembunyikan'], null, ['class' => 'form-control select2',
                    'required']) !!}
                    </div>
                    <div class="clearfix"></div>
                    <hr>

                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}                    
                    <a class="btn btn-sm btn-light" href="{!! route('nomenklaturs.show', @$tahun) !!}"> Cancel </a>

                    {!! Form::close() !!}
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    /* Remove default bullets */
    ul, #myUL {
        list-style-type: none;
    }

    /* Remove margins and padding from the parent ul */
    #myUL {
        margin: 0;
        padding: 0;
    }

    /* Style the caret/arrow */
    .caret, .dott {
        cursor: pointer;
        user-select: none; /* Prevent text selection */
    }

    /* Create the caret/arrow with a unicode, and style it */
    .caret::before {
        content: "\25B6";
        color: black;
        display: inline-block;
        margin-right: 6px;
    }

    .dott::before{
        content: "\1f89E";
        color: black;
        display: inline-block;
        margin-right: 6px;
    }

    /* Rotate the caret/arrow icon when clicked on (using JavaScript) */
    .caret-down::before {
        transform: rotate(90deg);
    }

    /* Hide the nested list */
    .nested {
    display: none;
    }

    /* Show the nested list when the user clicks on the caret/arrow (with JavaScript) */
    .active {
    display: block;
    }

</style>
@endsection

@section('scripts')
    <script src="{{ asset('vendor/dashforge/lib/cleave.js/cleave.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify({
                messages: {
                    default: 'Drag and drop file here or click',
                    replace: 'Drag and drop file here or click to Replace',
                    remove:  'Remove',
                    error:   'Sorry, the file is too large'
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

            var cleaveI = new Cleave('#kode', {
                delimiters: ['.', '.', '.', '.', '.'],
                blocks: [2,2,2,2,2,2]
            });            
        });
    </script>
@endsection
