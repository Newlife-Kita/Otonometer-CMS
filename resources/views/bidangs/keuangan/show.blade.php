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

            <h4 class="mg-b-20">Sektor/Bidang Keuangan</h4>
            <hr>
            @include('flash::message')

            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th colspan="2">Sektor/ Bidang</th>
                        <th>Tahun Nomenklatur</th>
                        <th colspan="2">
                            @can('bidang-create')
                                <a class="btn btn-icon btn-primary btn-xs" href="{!! route('sektor-bidang.keuangan.create', @$code) !!}"><i
                                        class="fa fa-plus"></i></a>
                            @endcan
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bidang as $bid)
                        <tr class="{{ $bid['cls_str'] }}" id="tr-{{ $bid['id']}}">
                            <td>
                                @if ($bid['child'] == true)
                                    <button class="btn btn-outline-dark btn-icon btn-xs btn-collapse open"
                                        data-id="{{ $bid['id'] }}"><i class="fas fa-chevron-down"></i></button>
                                @endif
                            </td>
                            <td><span>{!! $bid['code'] !!} - {!! $bid['name'] !!}</span></td>
                            <td>{{ $bid['tahun'] }} </td>
                            <td>
                                <div class="btn-group">
                                    @can('bidang-create')
                                        <a class="btn btn-icon btn-outline-primary btn-xs" href="{!! route('sektor-bidang.keuangan.create', @$bid['id']) !!}"><i
                                                class="fa fa-plus"></i></a>
                                    @endcan
                                    @can('bidang-edit')
                                        <a href="{{ route('sektor-bidang.keuangan.edit', $bid['id']) }}"
                                            class='btn btn-outline-success btn-xs btn-icon'>
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('bidang-delete')
                                        {!! Form::open([
                                            'route' => ['sektor-bidang.keuangan.destroy', $bid['id']],
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
                            <td style="text-align:center">
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


        $('.root-badge').click(function() {
            Swal.fire({
                title: 'Sektor/Bidang Utama',
                text: 'Sektor/Bidang utama adalah sektor yang menjadi akar dari seluruh cabang sektor lainnya di bawahnya',
                icon: 'info'
            });
        });

        $('.alert-badge').click(function() {
            Swal.fire({
                title: 'Pop up',
                text: 'Sektor yang memiliki badge ini akan memicu pop up ketika dipilih oleh user.',
                icon: 'info'
            });
        });

        $('.summable-badge').click(function() {
            Swal.fire({
                title: 'Tidak Dapat Ditotal',
                text: 'Sektor yang memiliki badge ini tidak memiliki opsi SEMUA pada pilihan.',
                icon: 'info'
            });
        });

        $('.nondata-badge').click(function() {
            Swal.fire({
                title: 'Tidak Memiliki Data/Nilai',
                text: 'Sektor yang memiliki badge ini tidak memiliki nilai atau data sehingga tidak dapat dipilih oleh user.',
                icon: 'info'
            });
        });

        $('.year-badge').click(function() {
            Swal.fire({
                title: 'Tidak Memiliki Nomenklatur',
                text: 'Sektor yang memiliki badge ini tidak memiliki nomenklatur. Mohon untuk mengisi nomenklatur agar fungsi dapat berjalan dengan baik',
                icon: 'info'
            });
        });

        $('.nonvalid-badge').click(function() {
            Swal.fire({
                title: 'Sektor belum valid',
                text: 'Sektor belum valid dikarenakan tidak memiliki sektor /bidang utama.',
                icon: 'info'
            });
        });
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
