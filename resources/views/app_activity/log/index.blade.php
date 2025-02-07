@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Aktivitas Applikasi/ Log Harian</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">Log Harian Aktivitas User Otonometer</h4>

            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div class="wd-40p pd-t-20">
                    <div>
                        <a href="{{ route('log.download') }}" class="btn btn-success btn-download"><i
                                class="fas fa-file-excel"></i>
                            Download Data</a>
                    </div>
                </div>
            </div>

            <hr />

            <div class="table-responsive">
                <table class="table table-border table-striped table-hover table-result">
                    <thead class="thead-dark">
                        <tr>
                            <th><button class="btn btn-unstyled text-white">Tanggal</button>
                            </th>
                            <th>Modul Halaman</th>
                            <th><button class="btn btn-unstyled text-white">Total Lihat</button>
                            </th>
                            <th><button class="btn btn-unstyled text-white">Total Share</button>
                            </th>
                            <th><button class="btn btn-unstyled text-white">Total Download</button>
                            </th>
                            <th><button class="btn btn-unstyled text-white">Total Simpan</button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" class="tx-center">Data Not Found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let mode = "daily";

        $(document).ready(function() {
            fetchLog();
        });

        // $('th button.btn').click(function() {
        //     let clickedButton = $(this); // Get the clicked button element
        //     let parentTh = clickedButton.closest('th'); // Get the parent th element

        //     // setTimeout(() => {
        //     //     $('th button').each(function(index, element) {
        //     //         if (!clickedButton.is($(element))) {
        //     //             let textOfButton = $(element).text();
        //     //             $(element).empty();
        //     //             $(element).text(textOfButton);
        //     //         }
        //     //     });
        //     // }, 0);


        //     // $('th button').each(function(ind))

        // });

        function fetchLog() {
            $.ajax({
                url: "{!! route('log.daily') !!}",
                beforeSend: function() {
                    $('.table-result>tbody').html(`<tr><td class="tx-center" colspan="5"><div class="text-center"><div class="spinner-border">...</div>
                    </div></td></tr>`);
                },
                success: function(result) {
                    let html_body = "";
                    if (result.items.length > 0) {
                        const options = {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        }
                        $.each(result.items, function(index, value) {

                            const date = new Date(value.tanggal_log);
                            const localizedDateString = date.toLocaleDateString('id-ID', options);

                            html_body += `<tr><td rowspan="3" class="text-left pr-10">${localizedDateString}</td>
                            <td class="text-center pr-10">Jelajah</td>
                            <td class="text-center pr-10">${value.total_view_jelajah}</td>
                            <td class="text-center pr-10">${value.total_share_jelajah}</td>
                            <td class="text-center pr-10">${value.total_download_jelajah}</td>
                            <td class="text-center pr-10">${value.total_save_jelajah}</td></tr>
                            <tr>
                            <td class="text-center pr-10">Utak-Atik</td>
                            <td class="text-center pr-10">${value.total_view_utak_atik}</td>
                            <td class="text-center pr-10">${value.total_share_utak_atik}</td>
                            <td class="text-center pr-10">${value.total_download_utak_atik}</td>
                            <td class="text-center pr-10">${value.total_save_utak_atik}</td></tr>
                            <tr>
                            <td class="text-center pr-10">Berkaca</td>
                            <td class="text-center pr-10">${value.total_view_berkaca}</td>
                            <td class="text-center pr-10">${value.total_share_berkaca}</td>
                            <td class="text-center pr-10">${value.total_download_berkaca}</td>
                            <td class="text-center pr-10">${value.total_save_berkaca}</td></tr>`;
                        });

                    } else {
                        html_body +=
                            `<tr><td class="tx-center" colspan="5">Data not found</td></tr>`;
                    }

                    $('.table-result>tbody').html(html_body);
                }
            });
        }
    </script>
@endsection
