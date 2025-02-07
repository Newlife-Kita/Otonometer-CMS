<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $nomenklaturtahun->id !!}</p>
</div>

<!-- Id Nomenklatur Field -->
<div class="form-group">
    {!! Form::label('id_nomenklatur', 'Id Nomenklatur:') !!}
    <p>{!! $nomenklaturtahun->id_nomenklatur !!}</p>
</div>

<!-- Tahun Field -->
<div class="form-group">
    {!! Form::label('tahun', 'Tahun:') !!}
    <p>{!! $nomenklaturtahun->tahun !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $nomenklaturtahun->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $nomenklaturtahun->updated_at !!}</p>
</div>

<!-- Deleted At Field -->
<div class="form-group">
    {!! Form::label('deleted_at', 'Deleted At:') !!}
    <p>{!! $nomenklaturtahun->deleted_at !!}</p>
</div>

