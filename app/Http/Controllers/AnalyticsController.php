<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\GoogleAnalytics;
use Analytics;
use Spatie\Analytics\Period;

class AnalyticsController extends Controller
{

    public function test()
    {
        $start_date = \Carbon\Carbon::yesterday();
        $end_date = \Carbon\Carbon::yesterday();
        $period = Period::create($start_date, $end_date);
        
        /*
        $metrics = 'ga:pageviews,ga:adsenseRevenue,ga:adsensePageImpressions';
        $others = ['dimensions' => 'ga:pagePath'];
        $analyticsData = Analytics::performQuery($period, $metrics, $others);
        $rows = $analyticsData->rows;
        */
        
        $rows = [['/pages/1/article-slug-1', '75', '1.10'], ['/pages/2/article-slug-2', '100', '2.5'], ['/categories/2/something', '10', '0.0']];

        foreach($rows as $row) {
            if(explode('/', $row[0])[1] == "pages") { 
                GoogleAnalytics::create([
                    'page_id' => explode('/', $row[0])[2], 
                    'start_date' => $start_date,
                    'page_views' => $row[1], 
                    'adsense_revenue' => $row[2]
                ]);
            }
        }
        return response()->json(['status' => 'success']);
    }

    public function testAll()
    {
        $start_date = \Carbon\Carbon::yesterday();
        $end_date = \Carbon\Carbon::yesterday();
        $period = Period::create($start_date, $end_date);
        
        $metrics = 'ga:pageviews,ga:adsenseRevenue,ga:adsensePageImpressions';
        //$others = ['dimensions' => 'ga:pagePath', 'filters' => 'ga:pagePath==/data-modelling/dimensional-model/58-top-50-dwbi-interview-questions-with-answers'];   
        $others = ['dimensions' => 'ga:pagePath'];
        
        $analyticsData = Analytics::performQuery($period, $metrics, $others);
        
        // $totalsForAllResults = $analyticsData->totalsForAllResults;
        // $page_views = $totalsForAllResults['ga:pageviews'];

        return response()->json($analyticsData->rows);
    }
}
