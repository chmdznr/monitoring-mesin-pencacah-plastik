<?php

namespace App\Http\Controllers\Admin;

use App\Models\PembacaanSensor;
use Illuminate\Support\Facades\Http;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class HomeController
{
    public function index()
    {
        $settings1 = [
            'chart_title'           => 'Data Terbaru',
            'chart_type'            => 'latest_entries',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\PembacaanSensor',
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'Y-m-d H:i:s',
            'column_class'          => 'col-md-12',
            'entries_number'        => '5',
            'fields'                => [
                'id'         => '',
                'energi'     => '',
                'berat'      => '',
                'created_at' => '',
            ],
            'translation_key' => 'pembacaanSensor',
        ];

        $settings1['data'] = [];
        if (class_exists($settings1['model'])) {
            $settings1['data'] = $settings1['model']::orderBy('id', 'desc')
                ->take($settings1['entries_number'])
                ->get();
        }

        if (! array_key_exists('fields', $settings1)) {
            $settings1['fields'] = [];
        }

        $settings2 = [
            'chart_title'           => 'Rata-rata Energi (7 hari)',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\PembacaanSensor',
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'avg',
            'aggregate_field'       => 'energi',
            'filter_field'          => 'created_at',
            'filter_days'           => '7',
            'group_by_field_format' => 'Y-m-d H:i:s',
            'column_class'          => 'col-md-6',
            'entries_number'        => '5',
            'translation_key'       => 'pembacaanSensor',
        ];

        $settings2['total_number'] = 0;
        if (class_exists($settings2['model'])) {
            $settings2['total_number'] = $settings2['model']::when(isset($settings2['filter_field']), function ($query) use ($settings2) {
                if (isset($settings2['filter_days'])) {
                    return $query->where($settings2['filter_field'], '>=',
                        now()->subDays($settings2['filter_days'])->format('Y-m-d'));
                } elseif (isset($settings2['filter_period'])) {
                    switch ($settings2['filter_period']) {
                        case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                            break;
                        case 'month': $start = date('Y-m') . '-01';
                            break;
                        case 'year': $start = date('Y') . '-01-01';
                            break;
                    }
                    if (isset($start)) {
                        return $query->where($settings2['filter_field'], '>=', $start);
                    }
                }
            })
                ->{$settings2['aggregate_function'] ?? 'count'}($settings2['aggregate_field'] ?? '*');
        }

        $settings3 = [
            'chart_title'           => 'Rata-rata Berat (7 hari)',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\PembacaanSensor',
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'avg',
            'aggregate_field'       => 'berat',
            'filter_field'          => 'created_at',
            'filter_days'           => '7',
            'group_by_field_format' => 'Y-m-d H:i:s',
            'column_class'          => 'col-md-6',
            'entries_number'        => '5',
            'translation_key'       => 'pembacaanSensor',
        ];

        $settings3['total_number'] = 0;
        if (class_exists($settings3['model'])) {
            $settings3['total_number'] = $settings3['model']::when(isset($settings3['filter_field']), function ($query) use ($settings3) {
                if (isset($settings3['filter_days'])) {
                    return $query->where($settings3['filter_field'], '>=',
                        now()->subDays($settings3['filter_days'])->format('Y-m-d'));
                } elseif (isset($settings3['filter_period'])) {
                    switch ($settings3['filter_period']) {
                        case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                            break;
                        case 'month': $start = date('Y-m') . '-01';
                            break;
                        case 'year': $start = date('Y') . '-01-01';
                            break;
                    }
                    if (isset($start)) {
                        return $query->where($settings3['filter_field'], '>=', $start);
                    }
                }
            })
                ->{$settings3['aggregate_function'] ?? 'count'}($settings3['aggregate_field'] ?? '*');
        }

        $chart4 = [
            'labels' => PembacaanSensor::selectRaw('DATE(created_at) as date')
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get()
                ->pluck('date')
                ->toArray(),
            'datasets' => [
                [
                    'label' => 'Banyaknya data masuk (Energi)',
                    'data' => PembacaanSensor::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                        ->groupBy('date')
                        ->orderBy('date', 'asc')
                        ->get()
                        ->pluck('count')
                        ->toArray(),
                    'borderColor' => 'rgba(82, 27, 41, 0.2)',
                    'borderWidth' => 2,
                    'fill' => false
                ]
            ]
        ];

        $chart5 = [
            'labels' => PembacaanSensor::selectRaw('DATE(created_at) as date')
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get()
                ->pluck('date')
                ->toArray(),
            'datasets' => [
                [
                    'label' => 'Banyaknya data masuk (Berat)',
                    'data' => PembacaanSensor::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                        ->groupBy('date')
                        ->orderBy('date', 'asc')
                        ->get()
                        ->pluck('count')
                        ->toArray(),
                    'borderColor' => 'rgba(55, 94, 127, 0.2)',
                    'borderWidth' => 2,
                    'fill' => false
                ]
            ]
        ];

        $latestEnergiData = PembacaanSensor::orderBy('id', 'desc')
            ->take(20)
            ->get()
            ->reverse(); // Reverse the collection to have the oldest first

        $labelsEnergi = $latestEnergiData->pluck('id')->toArray();
        $valuesEnergi = $latestEnergiData->pluck('energi')->toArray();

        $latestBeratData = PembacaanSensor::orderBy('id', 'desc')
            ->take(20)
            ->get()
            ->reverse(); // Reverse the collection to have the oldest first

        $labelsBerat = $latestBeratData->pluck('id')->toArray();
        $valuesBerat = $latestBeratData->pluck('berat')->toArray();

        return view('home', compact('chart4', 'chart5', 'settings1', 'settings2', 'settings3', 'labelsEnergi', 'valuesEnergi', 'labelsBerat', 'valuesBerat'));
    }

    public function latestAnomaly()
    {
        // Fetch the latest anomaly detection result
        $anomalyServiceUrl = 'https://pencacah2024.msvc.app/svc/detect-anomalies';
        $response = Http::post($anomalyServiceUrl);
        return response()->json($response->json());
    }

    public function getLatestData()
    {
        $latestEnergiData = PembacaanSensor::orderBy('id', 'desc')
            ->take(20)
            ->get()
            ->reverse();

        $labelsEnergi = $latestEnergiData->pluck('id')->toArray();
        $valuesEnergi = $latestEnergiData->pluck('energi')->toArray();

        $latestBeratData = PembacaanSensor::orderBy('id', 'desc')
            ->take(20)
            ->get()
            ->reverse();

        $labelsBerat = $latestBeratData->pluck('id')->toArray();
        $valuesBerat = $latestBeratData->pluck('berat')->toArray();

        $chart4Data = [
            'labels' => PembacaanSensor::selectRaw('DATE(created_at) as date')
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get()
                ->pluck('date')
                ->toArray(),
            'data' => PembacaanSensor::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get()
                ->pluck('count')
                ->toArray()
        ];

        $chart5Data = [
            'labels' => PembacaanSensor::selectRaw('DATE(created_at) as date')
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get()
                ->pluck('date')
                ->toArray(),
            'data' => PembacaanSensor::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get()
                ->pluck('count')
                ->toArray()
        ];

        $tableData = PembacaanSensor::orderBy('id', 'desc')
            ->take(5)
            ->get()
            ->toArray();

        return response()->json([
            'labelsEnergi' => $labelsEnergi,
            'valuesEnergi' => $valuesEnergi,
            'labelsBerat' => $labelsBerat,
            'valuesBerat' => $valuesBerat,
            'chart4' => $chart4Data,
            'chart5' => $chart5Data,
            'tableData' => $tableData
        ]);
    }
}
