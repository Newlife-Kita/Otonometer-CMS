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
            
            <a href="{{ route('sektor-bidang.statistik.index') }}" class="btn btn-sm btn-primary mg-b-10"><i class="fa fa-chevron-left"></i> LIST BIDANG/SEKTOR STATISTIK</a>
            <div class="alert alert-warning mg-b-20" role="alert">
                Pilih Anakan/Child dari Sektor/Bidang yang akan di kelompokan/Grouping</b>
            </div>
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th colspan="2">Sektor/ Bidang</th>
                        <th>Kode Group</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bidang as $bid)
                    <tr class="{{ $bid["cls_str"] }}">
                        <td>
                            @if($bid['child'] == true)
                                <button class="btn btn-outline-dark btn-icon btn-xs btn-collapse open" data-id="{{$bid["id"]}}"><i class="fas fa-chevron-down"></i></button>
                            @endif
                        </td>  
                        <td><span>{!! $bid['code'] !!} - {!! $bid['name'] !!}</span></td>
                        <td>
                            <div class="btn-group">
                                <a class="btn btn-outline-primary btn-xs" href="{!! route('sektor-bidang.statistik.form-grouping', @$bid['id']) !!}"><i class="fa fa-list"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
