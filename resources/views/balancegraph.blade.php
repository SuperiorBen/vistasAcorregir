@extends('layouts1m2')
@section('content')
    @include('searchform', ['subtitle' => 'Balance graph'])
    <div>
        <canvas id="myChart"></canvas>
    </div>
    @if (isset($totales))

        <table id='tdata' style='font-size: small;' class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Currency ID</th>
                    <th>Total</th>
                    <th>Aprox. USD</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($totales as $total)
                    <tr>
                        <td>{{ $total->fecha }}</td>
                        <td>{{ $total->USD }}</td>
                        <td>{{ $total->BTC }}</td>
                        <td>{{ $total->aprox_usd }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

@endsection

@section('script')
    @if (isset($totales))
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.0/chart.min.js"
        integrity="sha512-GMGzUEevhWh8Tc/njS0bDpwgxdCJLQBWG3Z2Ct+JGOpVnEmjvNx6ts4v6A2XJf1HOrtOsfhv3hBKpK9kE5z8AQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <script type="text/javascript">
        const data = {
            labels: {!! $dates !!},
            datasets: [{
                    label: 'USD',
                    backgroundColor: 'rgb(255, 99, 132)',
                    borderColor: 'rgb(255, 99, 132)',
                    data: {!! $usd !!},
                    yAxisID: 'y'
                },
                {
                    label: 'BTC (aprox. in USD)',
                    backgroundColor: '#00B8EA',
                    borderColor: '#00ADD6',
                    data: {!! $aprox_usd !!},
                    yAxisID: 'y1',
                }
            ]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',

                        // grid line settings
                        grid: {
                            drawOnChartArea: false, // only want the grid lines for one axis to show up
                        },
                    }
                }
            }
        };

        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );
    </script>
    @endif
@endsection
