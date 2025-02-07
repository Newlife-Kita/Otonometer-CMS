<!-- Id Wilayah Field -->
<div class="row">
    <div class="col-sm-6">
        <div class="form-group col-sm-10">
            {!! Form::label('id_wilayah', 'Provinsi/Kabupaten/Kota:') !!}
            {!! Form::text('wilayah', $wilayah->nama, [
                'class' => 'form-control',
                'disabled',
            ]) !!}
            {!! Form::hidden('id_wilayah', @$wilayah->id) !!}
        </div>

        <!-- Nama Sudin Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('nama_sudin', 'Nama Sudin:', ['class' => 'd-block']) !!}
            @foreach ($bahasa as $lg)
                @if ($loop->index > 0)
                    <div class="mg-t-10"></div>
                @endif
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{ $lg->label }} ({{ $lg->code }})</span>
                    </div>
                    {!! Form::text(
                        'nama_sudin[' . $lg->code . ']',
                        !empty($sudin->nama_sudin) ? $sudin->getTranslation('nama_sudin', $lg->code) : null,
                        ['class' => 'form-control'],
                    ) !!}
                </div>
            @endforeach
        </div>

        <!-- Alamat Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('alamat', 'Alamat:', ['class' => 'd-block']) !!}
            {!! Form::textarea('alamat', @$sudin->alamat, ['class' => 'form-control', 'rows' => 2]) !!}
        </div>

        <!-- Nama Pic Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('nama_pic', 'Nama Pic:', ['class' => 'd-block']) !!}
            {!! Form::text('nama_pic', @$sudin->nama_pic, ['class' => 'form-control']) !!}
        </div>

        <!-- Telp Pic Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('telp_pic', 'Telp Pic:', ['class' => 'd-block']) !!}
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">+62</span>
                </div>
                {!! Form::number('telp_pic', @$sudin->telp_pic, ['class' => 'form-control']) !!}
            </div>
        </div>

        <!-- Email Pic Field -->
        <div class="form-group col-sm-10">
            {!! Form::label('email_pic', 'Email Pic:', ['class' => 'd-block']) !!}
            {!! Form::email('email_pic', @$sudin->email_pic, ['class' => 'form-control']) !!}
        </div>
    </div>
    <!-- Logo Field -->
    <div class="form-group col-sm-3">
        {!! Form::label('logo', 'Logo:', ['class' => 'd-block']) !!}
        {!! Form::file('logo', [
            'class' => 'form-control dropify',
            'data-default-file' => @$sudin->logo ? getFileUrl(@$sudin->logo) : '',
            'data-max-file-size' => '2M',
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
