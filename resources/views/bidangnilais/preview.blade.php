@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Nilai Sektor/Bidang</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <h4 class="mg-b-10">Konfirmasi Nilai Sektor/Bidang</h4>
            
            {!! Form::open(['route' => 'bidangnilais.submit', 'files' => true]) !!}  
            <div class="table-responsive">
                <table class="table table-border table-result bd bd-1">
                    <thead class="thead-dark">
                        <tr>
                            <th>Kode</th>
                            <th>Lokasi/ Daerah</th>
                            <th>Tahun</th>
                            @foreach($header as $head)
                                <th>{{ $head->kode_bidang }}. {{ $head->nama_bidang }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $dat)
                            <tr class="{{ empty($dat['id']) ? 'bg-danger' : '' }}">
                                <td class=" bd bd-1">{{ $dat['kode'] }}</td>
                                <td class=" bd bd-1">{{ $dat['nama']}}</td>
                                @foreach($dat['details'] as $details)
                                    @if($loop->index < 1)
                                        <td class=" bd bd-1">{{ $details['tahun'] }}</td>
                                    @endif
                                    <td class="bd bd-1 {{ $details['nilai'] == 'NULL' ? 'bg-danger' : '' }}">
                                        {!! $details['nilai'] !!}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="clearfix"></div>            
            <hr>
            
            @if(in_array('err', $err))
                <div class="alert alert-danger" role="alert">Beberapa data dalam excel tidak sesuai, mohon di perbaiki dan upload ulang!</div>
            @endif

            <!-- Submit Field -->
            <div class="form-group col-sm-12">
                @if(!in_array('err', $err))
                {!! Form::submit('Simpan Data', ['class' => 'btn btn-primary']) !!}
                @endif
                <a href="{!! route('bidangnilais.create') !!}" class="btn btn-light">Cancel</a>
            </div>            
            {!! Form::close() !!}
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('styles')
    <style>
        .input-group .select2-container {
            width: 40% !important;
        }
    </style>
@endsection

@section('scripts')

@endsection
