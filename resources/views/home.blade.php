@extends('layouts.admin')
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Dashboard
                    </div>

                    <div class="panel-body">
                        @if(session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!-- New blocks for latest value and anomaly status -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-green" style="display:flex; flex-direction: column; justify-content: center;">
                                        <i class="fa fa-info-circle"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Latest Value</span>
                                        <span class="info-box-number" id="latest-value"></span>
                                        <span id="latest-timestamp" style="font-size: smaller; font-weight: normal;"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-orange" style="display:flex; flex-direction: column; justify-content: center;">
                                        <i class="fa fa-exclamation-triangle"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Anomaly Status</span>
                                        <span class="info-box-number" id="anomaly-status"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="{{ $settings2['column_class'] }}">
                                <div class="info-box">
                                    <span class="info-box-icon bg-red" style="display:flex; flex-direction: column; justify-content: center;">
                                        <i class="fa fa-chart-line"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ $settings2['chart_title'] }}</span>
                                        <span class="info-box-number">{{ number_format($settings2['total_number']) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="{{ $settings3['column_class'] }}">
                                <div class="info-box">
                                    <span class="info-box-icon bg-red" style="display:flex; flex-direction: column; justify-content: center;">
                                        <i class="fa fa-chart-line"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ $settings3['chart_title'] }}</span>
                                        <span class="info-box-number">{{ number_format($settings3['total_number']) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="{{ $chart4->options['column_class'] }}">
                                <h3>{!! $chart4->options['chart_title'] !!}</h3>
                                {!! $chart4->renderHtml() !!}
                            </div>

                            <div class="{{ $chart5->options['column_class'] }}">
                                <h3>{!! $chart5->options['chart_title'] !!}</h3>
                                {!! $chart5->renderHtml() !!}
                            </div>

                            <div class="col-md-6">
                                <h3>Latest 20 Data Points (Energi)</h3>
                                <canvas id="latestDataChartEnergi"></canvas>
                            </div>

                            <div class="col-md-6">
                                <h3>Latest 20 Data Points (Berat)</h3>
                                <canvas id="latestDataChartBerat"></canvas>
                            </div>

                            <div class="{{ $settings1['column_class'] }}" style="overflow-x: auto;">
                                <h3>{{ $settings1['chart_title'] }}</h3>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        @foreach($settings1['fields'] as $key => $value)
                                            <th>
                                                {{ trans(sprintf('cruds.%s.fields.%s', $settings1['translation_key'] ?? 'pleaseUpdateWidget', $key)) }}
                                            </th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($settings1['data'] as $entry)
                                        <tr>
                                            @foreach($settings1['fields'] as $key => $value)
                                                <td>
                                                    {{ $entry->{$key} }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ count($settings1['fields']) }}">{{ __('No entries found') }}</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    {!! $chart4->renderJs() !!}
    {!! $chart5->renderJs() !!}

    <script>
        const ctxLatestDataChartEnergi = document.getElementById('latestDataChartEnergi').getContext('2d');
        const latestDataChartEnergi = new Chart(ctxLatestDataChartEnergi, {
            type: 'line',
            data: {
                labels: @json($labelsEnergi),
                datasets: [{
                    label: 'Energi',
                    data: @json($valuesEnergi),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    fill: false
                }]
            },
            options: {
                tooltips: {
                    mode: 'point'
                },
                height: '300px',
                scales: {
                    xAxes: [],
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                },
            }
        });

        const ctxLatestDataChartBerat = document.getElementById('latestDataChartBerat').getContext('2d');
        const latestDataChartBerat = new Chart(ctxLatestDataChartBerat, {
            type: 'line',
            data: {
                labels: @json($labelsBerat),
                datasets: [{
                    label: 'Berat',
                    data: @json($valuesBerat),
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1,
                    fill: false
                }]
            },
            options: {
                tooltips: {
                    mode: 'point'
                },
                height: '300px',
                scales: {
                    xAxes: [],
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                },
            }
        });

        // Function to update the latest value and anomaly status
        function updateLatestData() {
            fetch('/api/latest-anomaly')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('latest-value').innerText = `${data.value}`;
                    document.getElementById('latest-timestamp').innerText = `(${data.created_at})`;
                    document.getElementById('anomaly-status').innerText = data.is_anomaly ? 'Anomaly Detected' : 'No Anomaly';
                })
                .catch(error => console.error('Error fetching latest anomaly data:', error));
        }

        // Initial fetch
        updateLatestData();

        // Update every 5 seconds
        setInterval(updateLatestData, 5000);
    </script>
@endsection
