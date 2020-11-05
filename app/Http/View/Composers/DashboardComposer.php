<?php

namespace App\Http\View\Composers;

use App\Charts\DashboardChart;
use Backpack\CRUD\app\Library\Widget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DashboardComposer
{

    public function __construct()
    {
        // Dependencies automatically resolved by service container...
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $pledgesChart = new DashboardChart();
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        $monthYear = Carbon::createFromDate(Carbon::now()->year, Carbon::now()->month , 1);
        $currentDate = $monthYear->toDateString();

        $allPledges = \App\Models\Pledge::selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as entry_date, sum(amount) as total')->whereMonth('created_at', $month)->whereYear('created_at', $year)->groupBy('entry_date')->get();
        $pledgesData = [];
        $daysThisMonth = []; // set temp vars
        // foreach($pledgesMonth)
        for ($i = 1; $i <= $monthYear->daysInMonth; $i++) {
            array_push($pledgesData, ($offset = $allPledges->firstWhere('entry_date', $currentDate)) ? $offset->total : null);

            array_push($daysThisMonth, $currentDate);
            $currentDate = $i < $monthYear->daysInMonth ? $monthYear->addDay()->toDateString() : $monthYear->toDateString();
        };
Log::info($allPledges);
        $pledgesChart->labels($daysThisMonth);
        $pledgesChart->dataset('Pledges', 'bar', $pledgesData)->color('#63C2DE');
        $pledgesChart->options([
            'scales' => [
                'yAxes' => [
                    [
                        'ticks' => [
                            'precision' => 0,
                        ],
                        'scaleLabel' => [
                            'display' => true,
                            'labelString' => '# of Pledges this month'
                        ]
                    ]
                ]
            ],
        ]);


        $view->with('pledgesChart', $pledgesChart);
    }
}
