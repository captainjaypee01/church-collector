<?php

namespace App\Http\Controllers\Admin\Charts;

use Backpack\CRUD\app\Http\Controllers\ChartController;
use Carbon\Carbon;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

/**
 * Class DashboardChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DashboardChartController extends ChartController
{
    public function setup()
    {
        $this->chart = new Chart();

        // MANDATORY. Set the labels for the dataset points
        $this->chart->labels([
            'Today',
        ]);

        // RECOMMENDED. Set URL that the ChartJS library should call, to get its data using AJAX.
        $this->chart->load(backpack_url('charts/dashboard'));

        // OPTIONAL
        $this->chart->minimalist(false);
        $this->chart->displayLegend(true);
    }

    /**
     * Respond to AJAX calls with all the chart data points.
     *
     * @return json
     */
    public function data()
    {
        $month = Carbon::now()->month - 1;
        $year = Carbon::now()->year;
        $monthYear = Carbon::createFromDate(Carbon::now()->year, Carbon::now()->month - 1, 1);
        $currentDate = $monthYear->toDateString();
        $pledgesMonth = \App\Models\Pledge::selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as entry_date, count(amount) as total')->whereMonth('created_at', $month)->whereYear('created_at', $year)->groupBy('entry_date')->get();
        $pledgesData = [];
        // foreach($pledgesMonth)
        for ($i = 1; $i <= $monthYear->daysInMonth; $i++) {
            array_push($pledgesData, ($offset = $pledgesMonth->firstWhere('entry_date', $currentDate)) ? $offset->total : null);
        };
        $this->chart->dataset('Pledges of the Month', 'bar', [
                    $pledgesData,
                ])
            ->color('rgba(205, 32, 31, 1)')
            ->backgroundColor('rgba(205, 32, 31, 0.4)');
    }
}
