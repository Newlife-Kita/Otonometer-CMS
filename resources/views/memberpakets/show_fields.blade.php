<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $memberpaket->id !!}</p>
</div>

<!-- Id Paket Field -->
<div class="form-group">
    {!! Form::label('id_paket', 'Id Paket:') !!}
    <p>{!! $memberpaket->id_paket !!}</p>
</div>

<!-- Id Member Field -->
<div class="form-group">
    {!! Form::label('id_member', 'Id Member:') !!}
    <p>{!! $memberpaket->id_member !!}</p>
</div>

<!-- Tanggal Mulai Field -->
<div class="form-group">
    {!! Form::label('tanggal_mulai', 'Tanggal Mulai:') !!}
    <p>{!! $memberpaket->tanggal_mulai !!}</p>
</div>

<!-- Tanggal Akhir Field -->
<div class="form-group">
    {!! Form::label('tanggal_akhir', 'Tanggal Akhir:') !!}
    <p>{!! $memberpaket->tanggal_akhir !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $memberpaket->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $memberpaket->updated_at !!}</p>
</div>

<!-- Deleted At Field -->
<div class="form-group">
    {!! Form::label('deleted_at', 'Deleted At:') !!}
    <p>{!! $memberpaket->deleted_at !!}</p>
</div>

