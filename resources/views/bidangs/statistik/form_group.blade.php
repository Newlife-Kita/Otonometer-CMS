@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Grouping Sektor/Bidang Statistik</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <h4 class="mg-b-20">Grouping Sektor/Bidang Statistik</h4>
            <hr>
            @include('flash::message')
            
            
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

            <div class="alert alert-warning mg-b-20" role="alert">
                Pastikan Menggunakan Kode yang berbeda jika Sektor/ Bidang Di disabled ketika Sektor/Bidang Lain di centang <br />
                <b>Penggabungan Group bisa menggunakan tanda koma (,)<br />
                Input Kelompok/ Grouping tanpa spasi</b>
            </div>
            <a href="#modalSample" class="btn btn-outline-dark mg-b-20" data-toggle="modal">Contoh Pada Aplikasi</a>
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th colspan="2">Sektor/ Bidang</th>
                        <th>Kode Group</th>
                    </tr>
                </thead>
                {!! Form::open(['route' => ['sektor-bidang.statistik.store_grouping']]) !!}
                <tbody>
                    @foreach($bidang as $bid)
                    <tr class="{{ $bid["cls_str"] }}">
                        <td>
                            @if($bid['child'] == true)
                                <button type="button" class="btn btn-outline-dark btn-icon btn-xs btn-collapse open" data-id="{{$bid["id"]}}"><i class="fas fa-chevron-down"></i></button>
                            @endif
                        </td>  
                        <td><span>{!! $bid['code'] !!} - {!! $bid['name'] !!}</span></td>
                        <td>
                            {!! Form::hidden('id[]', $bid["id"]) !!}
                            {!! Form::text('code[]', $bid["tahun"], ['class' => 'form-control tx-bold']) !!}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                            <a class="btn btn-sm btn-light" href="{!! route('sektor-bidang.statistik.grouping') !!}"> Cancel </a>
                        </td>
                </tfoot>                
                {!! Form::close() !!}
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalSample" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
          <div class="modal-content tx-14">
            <div class="modal-header">
              <h6 class="modal-title" id="exampleModalLabel3">Contoh dalam Aplikasi</h6>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-8">
                        <img src="{{ asset('sample_statistik.jpg') }}" >
                    </div>
                    <div class="col-sm-4">
                        <ul class="list-group">
                            <li class="list-group-item">1. Jika Bidang/Sektor Dapat di perbandingkan (centang bersamaan) maka gunakan kode yang sama (Hingga Ke Anakan)</li>
                            <li class="list-group-item bg-warning">Contoh Kode 1.2 digunakan untuk Laki-laki dan Perempuan</li>
                            <li class="list-group-item">2. Gunakan Kode Berbeda jika tidak dapat di bandingkan (centang bersamaan)</li>
                            <li class="list-group-item bg-warning">Contoh Kode 1.1 untuk Semua</li>
                            <li class="list-group-item">3. Untuk Parent Jika memiliki Anakan dengan berbagai macam kode maka Semua Kode anakan wajib di input di parent</li>
                            <li class="list-group-item bg-warning">Contoh Pada Bidang/Sektor Jumlah Penduduk dan Kepadatan Penduduk</li>
                        </ul>
                    </div>
                </div>
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary tx-13" data-dismiss="modal">Close</button>
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
    <script>
        $(function(){
            $('[data-toggle="popover"]').popover();
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
        });
    </script>
@endsection
