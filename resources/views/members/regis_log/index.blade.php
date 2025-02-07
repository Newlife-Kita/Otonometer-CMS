@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Log Register Member</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">Log Jumlah User Mendaftar Pada Setiap Hari</h4>

            <hr />


            <div class="w-100" style="height: auto">
                <canvas id="chart" style="width: 100%; height: auto" width="200" height="50"></canvas>
            </div>

            <div class="table-responsive">
                <table class="table table-border table-striped table-hover table-result">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th><button class="btn btn-unstyled text-white">Tanggal</button>
                            </th>
                            <th class="text-center"><button class="btn btn-unstyled text-white">Total Pendaftar</button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="7" class="tx-center">Data Not Found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
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

        var data = {
            labels: [],
            datasets: [{
                label: 'Jumlah User',
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                data: [],
                tension: 0,
            }],
        }


        var ctx = document.getElementById('chart');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: data,
        });

        function fetchLog() {
            $.ajax({
                url: "{!! route('members.ajax-log-register') !!}",
                beforeSend: function() {
                    $('.table-result>tbody').html(`<tr><td class="tx-center" colspan="3"><div class="text-center"><div class="spinner-border">...</div>
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
                        console.log(result.items);
                        const aggregatedData = {};
                        $.each(result.items, function(index, value) {
                            const date = new Date(value.date);
                            const localizedDateString = date.toLocaleDateString('id-ID', options);
                            const monthYear = date.toLocaleString('default', {
                                month: 'short',
                                year: 'numeric'
                            });

                            html_body += `<tr><td  class="">${index + 1}</td>
                                <td  class="text-left pr-10">${localizedDateString}</td>
                                <td  class="text-center pr-10">${value.member_count}</td></tr>`;

                            if (!aggregatedData[monthYear]) {
                                aggregatedData[monthYear] = 0;
                            }
                            aggregatedData[monthYear] += value.member_count;
                        });

                        // Populate the chart data
                        data.labels = Object.keys(aggregatedData);
                        data.datasets[0].data = Object.values(aggregatedData);

                        myChart.update();

                    } else {
                        html_body +=
                            `<tr><td class="tx-center" colspan="7">Data not found</td></tr>`;
                    }

                    $('.table-result>tbody').html(html_body);
                }
            });
        }
    </script>
@endsection
