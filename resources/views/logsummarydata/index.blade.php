@extends('layouts.app')

@section('contents')
    <div class="content content-components">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Log Ringkasan Data</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @include('flash::message')

            <h4 class="mg-b-10">Ringkasan Data</h4>

            <p class="mg-b-30">
                Disini anda dapat mengetahui ringkasan jumlah data yang terdapat pada sistem otonometer.
            </p>

            <div class="d-sm-flex align-items-center justify-content-between flex-column mg-b-20 mg-lg-b-25 mg-xl-b-30">
                <div class="d-flex justify-content-between w-75 mx-auto" style="margin-bottom: 30px">
                    <div class="d-flex flex-column align-items-center">
                        <h2>Jumlah Kesuluruhan Data</h2>
                        <p style="font-size: 20px; font-weight: semibold">{{ number_format($countData, 0, ',', '.') }}</p>
                    </div>
                    <div class="d-flex flex-column align-items-center">
                        <h2>Jumlah Tahun Data</h2>
                        <p style="font-size: 20px; font-weight: bold">{{ $totalTables }}</p>
                    </div>
                </div>

                <div class="w-100" style="height: auto">
                    <canvas id="chart" style="width: 100%; height: auto" width="200" height="50"></canvas>
                </div>
            </div>


        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <script>
           const options = {
            plugins: {
                title: {
                    display: true,
                    text: 'Chart.js Bar Chart - Stacked'
                },
            },
            responsive: true,
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true
                }
            }
        };

        const data = {
            labels: {!! json_encode($label) !!},
            datasets: [{
                    label: 'Keuangan',
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    data: {!! json_encode($data['keuangan']) !!}
                },
                {
                    label: 'Ekonomi',
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    data: {!! json_encode($data['ekonomi']) !!}
                },
                {
                    label: 'Statistik',
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    data: {!! json_encode($data['statistik']) !!}
                }
            ]
        };

        var ctx = document.getElementById('chart');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options
        });

     
    </script>
@endsection
