@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sektor/Bidang Keuangan</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <h4 class="mg-b-20">Teks Aplikasi Dua Bahasa</h4>
            <hr>
            @include('flash::message')

            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th colspan="2">Sektor/ Bidang</th>
                        <th colspan="1">
                            @can('bidang-create')
                                <a class="btn btn-icon btn-primary btn-xs" href="{!! route('app-structure.page.create') !!}"><i
                                        class="fa fa-plus"></i></a>
                            @endcan
                            <span class='btn btn-primary btn-xs btn-icon' id="toggle-show" data-show="false">
                                <i class="fas fa-eye-slash"></i> Hide all
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bidang as $bid)
                        <tr class="{{ $bid['cls_str'] }}" id="tr-{{ $bid['id'] }}">
                            <td>
                                @if ($bid['child'] == true)
                                    {!! $bid['button_prefix'] !!}
                                    <button class="btn btn-outline-dark btn-icon btn-xs btn-collapse open"
                                        data-id="{{ $bid['id'] }}" data-button="{{ $bid['id']}}"><i class="fas fa-chevron-down"></i></button>
                                @endif
                            </td>
                            <td><span>{!! $bid['name'] !!} - {!! $bid['property_name'] !!}</span></td>
                            <td class="text-right ">

                                <div class="btn-group">
                                    @can('bidang-create')
                                        @if ($bid['child'] == true)
                                            <a class="btn btn-icon btn-outline-primary btn-xs d-block"
                                                href="{!! route('app-structure.node.create', @$bid['id']) !!}"><i class="fa fa-plus"></i></a>
                                        @endif
                                    @endcan
                                    @can('bidang-edit')
                                        @if ($bid['child'] == false && !is_null($bid['text']))
                                            <a href="{{ route('app-text.text.edit', $bid['text']) }}"
                                                class='btn btn-outline-success btn-xs btn-icon d-block'>
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif
                                    @endcan
                                    @can('bidang-delete')
                                        {!! Form::open([
                                            'route' => ['app-structure.page.delete', $bid['id']],
                                            'method' => 'delete',
                                            'id' => 'table-form-' . $bid['id'],
                                        ]) !!}
                                        {!! Form::button('<i class="fa fa-trash"></i>', [
                                            'type' => 'button',
                                            'class' => 'btn btn-outline-danger btn-xs btn-icon btn table-del',
                                            'data-id' => $bid['id'],

                                        ]) !!}
                                        {!! Form::close() !!}
                                    @endcan
                                </div>
                            </td>
                            {{-- <td style="text-align:center">
                                @if ($bid['root'] == 'y')
                                    <span class='btn btn-warning btn-xs btn-icon rounded-circle root-badge'
                                        style="width: 25px; height: 25px; color:white;">
                                        <i class="fa fa-anchor"></i>
                                    </span>
                                @endif
                                @if ($bid['alert'] == 'y')
                                    <span class='btn btn-danger btn-xs btn-icon rounded-circle alert-badge'
                                        style="width: 25px; height: 25px; color:white;">
                                        <i class="fa fa-exclamation"></i>
                                    </span>
                                @endif
                                @if ($bid['summable'] == 'n')
                                    <span class='btn btn-primary btn-xs btn-icon rounded-circle summable-badge'
                                        style="width: 25px; height: 25px; color:white;">
                                        <i class="fa fa-not-equal"></i>
                                    </span>
                                @endif

                                @if ($bid['data'] == 'n')
                                    <span class='btn btn-danger btn-xs btn-icon rounded-circle nondata-badge'
                                        style="width: 25px; height: 25px; color:white;">
                                        <i class="fa fa-coins"></i>
                                    </span>
                                @endif

                                @if ($bid['tahun'] == '')
                                    <span class='btn btn-danger btn-xs btn-icon rounded-circle year-badge'
                                        style="width: 25px; height: 25px; color:white;">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                @endif


                            </td> --}}
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

        ul,
        #myUL {
            list-style-type: none;
        }

        /* Remove margins and padding from the parent ul */
        #myUL {
            margin: 0;
            padding: 0;
        }

        /* Style the caret/arrow */
        .caret,
        .dott {
            cursor: pointer;
            user-select: none;
            /* Prevent text selection */
        }

        /* Create the caret/arrow with a unicode, and style it */
        .caret::before {
            content: "\25B6";
            color: black;
            display: inline-block;
            margin-right: 6px;
        }

        .dott::before {
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
        $(function() {
            $(document).on('click', '.table-del', function(e) {
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
                        $("#table-form-" + id).submit();
                    }
                })
            });


        });
        $(document).on('click', '#toggle-show', function(e) {
            let status = $(this).attr('data-show');
            if (status == "false") {
                $(this).attr('data-show', 'true');
                $(this).html('<i class="fas fa-eye"></i> Show all');
                $('button[data-button]').removeClass('open');
                $('button[data-button]').html('<i class="fas fa-chevron-right"></i>');
                $('[class^="tr-"]').hide();
            } else {
                $(this).attr('data-show', 'false');
                $(this).html('<i class="fas fa-eye-slash"></i> Hide all');
                $('button[data-button]').addClass('open');
                $('button[data-button]').html('<i class="fas fa-chevron-down"></i>');
                $('[class^="tr-"]').show();

            }
        })

        
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.btn-collapse', function() {
                let thos = $(this);
                let id = thos.attr('data-id');
                if (thos.hasClass('open')) {
                    thos.removeClass('open');
                    thos.html('<i class="fas fa-chevron-right"></i>');
                    $(".tr-" + id).hide();
                } else {
                    thos.addClass('open');
                    thos.html('<i class="fas fa-chevron-down"></i>');
                    $(".tr-" + id).show();
                }
            });

            $.ajax({
                url: "{!! route('sektor-bidang.check-tree', 1) !!}",
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (!response.valid) {
                        response.nonvalid.forEach(element => {
                            $("#tr-" + element + ">td:nth-child(5)").append(
                                `<span class='btn btn-danger btn-xs btn-icon rounded-circle nonvalid-badge'
                                    style='width: 25px; height: 25px; color:white;'><i class='fa fa-times'></i></span>`
                            )
                        });

                        Swal.fire({
                            title: 'Terdapat sektor belum valid',
                            text: 'Terdapat sektor belum valid dikarenakan tidak memiliki sektor /bidang utama.',
                            icon: 'info'
                        });
                    }
                }
            });
        });
    </script>
@endsection
