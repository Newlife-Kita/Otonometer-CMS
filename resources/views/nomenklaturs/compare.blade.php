@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sektor/Bidang Tahun</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">Bandingkan Sektor/Bidang</h4>

            <div class="table-responsive">
                <table class="table table-border table-striped table-compare">
                    <thead class="thead-dark">
                        <tr>
                            <th>Master <button type="button" class="new-year btn btn-rounded btn-icon btn-primary btn-xs"><i class="fa fa-plus"></i></button></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                @foreach($bidang as $bid)
                                    <span class="d-block">{{ $bid["kode"] }} {{ $bid["nama"] }}</span>
                                @endforeach
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style></style>
@endsection


@section('scripts')
    <script>
        var row_klatur = 0;
        $(document).on('click','.new-year', function(){
            row_klatur++;
            $('.table-compare>thead>tr').append(`<th>
                {!! Form::select('year', $years, null, ['class' => 'form-control select2', 'id' => 'select_`+row_klatur+`']) !!}
            </th>`);
            $('.table-compare>tbody>tr').append('<td class="td-'+row_klatur+'"></td>');
            $(".select2").select2();

            $('#select_'+row_klatur).on('select2:select', function (e) { 
                var data = e.params.data;
                $.ajax({
                    url: "{{ route('nomenklaturs.getyears') }}",
                    data: { id : data.id },
                    dataType: "json",
                    success: function(res){
                        if(res.result.length > 0){
                            var html = '';
                            $.each(res.result, function( index, value ) {
                                html +='<span class="d-block">'+value.kode+' '+value.nama+'</span>';
                            });
                            $(".td-"+row_klatur).html(html);
                        }
                    }
                });
            });
        })
    </script>
@endsection

