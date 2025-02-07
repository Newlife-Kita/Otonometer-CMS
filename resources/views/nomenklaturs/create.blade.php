@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sektor/Bidang</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <h4 class="mg-b-20">Sektor/Bidang</h4>
            @include('flash::message')
            
            <div class="forms">
                {!! Form::open(['route' => 'nomenklaturs.store']) !!}        
                    <!-- Tahun Field -->
                    <div class="form-group col-sm-2 col-lg-2">                              
                        {!! Form::label('tahun_nomenklatur', 'Tahun Nomenklatur:', ['class' => 'd-block']) !!}                            
                        <div class="input-group">
                            {!! Form::text('tahun_nomenklatur', null, ['class' => 'form-control', 'required', 'placeholder' => 'yyyy', 'id' => 'tahun_nomenklatur']) !!}
                            <div class="input-group-append append-box"></div>
                        </div>
                    </div> 
                    <div class="form-group col-sm-6 col-lg-6">                              
                        {!! Form::label('description', 'Deskripsi:', ['class' => 'd-block']) !!}                            
                        {!! Form::text('description', null, ['class' => 'form-control', 'required', 'placeholder' => 'Deskripsi']) !!}
                    </div>    
                    <hr> 
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="wd-50">
                                        <button type="button" class="btn btn-light btn-icon btn-xs btn-head-collapse open" data-id="keuangan"><i class="fas fa-chevron-down"></i></button>
                                    </th>
                                    <th>Sektor/ Bidang Keuangan</th>
                                    <th class="wd-50">
                                        <div class="custom-control custom-checkbox">
                                            {!! Form::checkbox('use_keuangan', 'keuangan', true, ['class' => 'custom-control-input','id' => 'check_keuangan']) !!}
                                            <label class="custom-control-label" for="check_keuangan">All</label>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="tbody-keuangan">
                                @foreach(@$bidang["keuangan"] as $bid)
                                <tr class="{{ $bid["cls_str"] }}">
                                    <td>
                                        @if($bid['child'] == true)
                                            <button type="button" class="btn btn-outline-dark btn-icon btn-xs btn-collapse open" data-id="{{$bid["id"]}}"><i class="fas fa-chevron-down"></i></button>
                                        @endif
                                    </td>  
                                    <td><span>{!! $bid['code'] !!} - {!! $bid['name'] !!}</span></td>
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            {!! Form::hidden('year_row['.$bid['id'].']', $bid['tahun']) !!}
                                            {!! Form::checkbox('use_row[]', $bid['id'], true, ['class' => 'custom-control-input check-used-keuangan','id' => 'check_keuangan_'.$bid['id']]) !!}
                                            <label class="custom-control-label" for="check_keuangan_{{$bid['id']}}">Use</label>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="wd-50">
                                        <button type="button" class="btn btn-light btn-icon btn-xs btn-head-collapse open" data-id="ekonomi"><i class="fas fa-chevron-down"></i></button>
                                    </th>
                                    <th>Sektor/ Bidang Ekonomi</th>
                                    <th class="wd-50">
                                        <div class="custom-control custom-checkbox">
                                            {!! Form::checkbox('use_ekonomi', 'ekonomi', true, ['class' => 'custom-control-input','id' => 'check_ekonomi']) !!}
                                            <label class="custom-control-label" for="check_ekonomi">All</label>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="tbody-ekonomi">                    
                                @foreach(@$bidang["ekonomi"] as $bid)
                                <tr class="{{ $bid["cls_str"] }}">
                                    <td>
                                        @if($bid['child'] == true)
                                            <button type="button" class="btn btn-outline-dark btn-icon btn-xs btn-collapse open" data-id="{{$bid["id"]}}"><i class="fas fa-chevron-down"></i></button>
                                        @endif
                                    </td>  
                                    <td><span>{!! $bid['code'] !!} - {!! $bid['name'] !!}</span></td>
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            {!! Form::hidden('year_row['.$bid['id'].']', $bid['tahun']) !!}
                                            {!! Form::checkbox('use_row[]', $bid['id'], true, ['class' => 'custom-control-input check-used-ekonomi','id' => 'check_ekonomi_'.$bid['id']]) !!}
                                            <label class="custom-control-label" for="check_ekonomi_{{$bid['id']}}">Use</label>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <table class="mg-t-20 table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="wd-50">
                                        <button type="button" class="btn btn-light btn-icon btn-xs btn-head-collapse open" data-id="statistik"><i class="fas fa-chevron-down"></i></button>
                                    </th>
                                    <th>Sektor/ Bidang Statistik</th>
                                    <th class="wd-50">
                                        <div class="custom-control custom-checkbox">
                                            {!! Form::checkbox('use_statistik', 'statistik', true, ['class' => 'custom-control-input','id' => 'check_statistik']) !!}
                                            <label class="custom-control-label" for="check_statistik">All</label>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="tbody-statistik">          
                                @foreach(@$bidang["statistik"] as $bid)
                                <tr class="{{ $bid["cls_str"] }}">
                                    <td>
                                        @if($bid['child'] == true)
                                            <button type="button" class="btn btn-outline-dark btn-icon btn-xs btn-collapse open" data-id="{{$bid["id"]}}"><i class="fas fa-chevron-down"></i></button>
                                        @endif
                                    </td>  
                                    <td><span>{!! $bid['code'] !!} - {!! $bid['name'] !!}</span></td>
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            {!! Form::hidden('year_row['.$bid['id'].']', $bid['tahun']) !!}
                                            {!! Form::checkbox('use_row[]', $bid['id'], true, ['class' => 'custom-control-input check-used-statistik','id' => 'check_statistik_'.$bid['id']]) !!}
                                            <label class="custom-control-label" for="check_statistik_{{$bid['id']}}">Use</label>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                    <hr>

                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary btn-simpan']) !!}
                        <a href="{!! route('nomenklaturs.index') !!}" class="btn btn-light">Cancel</a>
                    </div>
                {!! Form::close() !!}
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
    <script>
        $(function(){
            $(document).on('click', '.table-del', function(e){
                let id = $(this).data('id');
                let name = $(this).data('name');
                Swal.fire({
                    title: 'Yakin hapus data?',
                    text: 'Sektor/ Bidang serta data akan terhapus secara permanen',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#0168fa',
                    cancelButtonColor: '#dc3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#table-form-"+id).submit();
                    }
                })
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.btn-collapse', function(){
                let thos = $(this);
                let id = thos.attr('data-id');
                if(thos.hasClass('open')){
                    thos.removeClass('open');
                    thos.html('<i class="fas fa-chevron-right"></i>');
                    $(".tr-"+id).hide();
                }
                else{
                    thos.addClass('open');
                    thos.html('<i class="fas fa-chevron-down"></i>');
                    $(".tr-"+id).show();
                }
            })
            
            $(document).on('click', '.btn-head-collapse', function(){
                let thos = $(this);
                let id = thos.attr('data-id');
                if(thos.hasClass('open')){
                    thos.removeClass('open');
                    thos.html('<i class="fas fa-chevron-right"></i>');
                    $(".tbody-"+id).hide();
                }
                else{
                    thos.addClass('open');
                    thos.html('<i class="fas fa-chevron-down"></i>');
                    $(".tbody-"+id).show();
                }
            })

            $(document).on('click', '#check_keuangan', function(){
                if($(this).prop('checked') == true){
                    $('.check-used-keuangan').prop('checked', true);
                }
                else{
                    $('.check-used-keuangan').prop('checked', false);
                }
            });  

            $(document).on('click', '.check-used-keuangan', function(){
                total_all = $(".check-used-keuangan").length;
                cek_all = $(".check-used-keuangan:checked").length;
                if(total_all == cek_all) $('#check_keuangan').prop('checked', true);
                else $('#check_keuangan').prop('checked', false);
            });
            
            $(document).on('click', '#check_ekonomi', function(){
                if($(this).prop('checked') == true){
                    $('.check-used-ekonomi').prop('checked', true);
                }
                else{
                    $('.check-used-ekonomi').prop('checked', false);
                }
            });  

            $(document).on('click', '.check-used-ekonomi', function(){
                total_all = $(".check-used-ekonomi").length;
                cek_all = $(".check-used-ekonomi:checked").length;
                if(total_all == cek_all) $('#check_ekonomi').prop('checked', true);
                else $('#check_ekonomi').prop('checked', false);
            });
            
            $(document).on('click', '#check_statistik', function(){
                if($(this).prop('checked') == true){
                    $('.check-used-statistik').prop('checked', true);
                }
                else{
                    $('.check-used-statistik').prop('checked', false);
                }
            });  

            $(document).on('click', '.check-used-statistik', function(){
                total_all = $(".check-used-statistik").length;
                cek_all = $(".check-used-statistik:checked").length;
                if(total_all == cek_all) $('#check_statistik').prop('checked', true);
                else $('#check_statistik').prop('checked', false);
            }); 
        });
    </script>
@endsection
