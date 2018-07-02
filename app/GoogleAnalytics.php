<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Analytics;
use Spatie\Analytics\Period;

class GoogleAnalytics extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $table = 'analytics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['page_id', 'start_date', 'page_views', 'adsense_revenue'];


    /**
     * Get the latest metrics associated with the page.
     */
    public static function metrics($page_id)
    {
        $metrics = DB::table('analytics')
                        ->select('page_id', DB::raw('SUM(page_views) as page_views'), DB::raw('SUM(adsense_revenue) as adsense_revenue'))
                        ->where('page_id', $page_id)
                        ->groupBy('page_id')
                        ->first();
        if(isset($metrics))
        return [
            'page_id' => $metrics->page_id, 
            'page_views' => self::prettyPrint($metrics->page_views), 
            'adsense_revenue' => self::prettyPrint($metrics->adsense_revenue)
        ];
        else
            return ['page_id' => $page_id, 'page_views' => '0', 'adsense_revenue' => '0'];
    }

    /**
     * Beautify Revenue & Page View Numbers.
     */
    private static function prettyPrint($num)
    {
        if ($num>=1000)
            return round($num/1000, 1) . 'K';
        else if ($num>100)
            return floor($num);
        else 
            return $num;
    }

    /**
     * Store Google Analytics data daily.
     */
    public function storeAnalyticsDaily()
    {
        $start_date = \Carbon\Carbon::yesterday();
        $end_date = \Carbon\Carbon::yesterday();
        $period = Period::create($start_date, $end_date);
        
        $metrics = 'ga:pageviews,ga:adsenseRevenue,ga:adsensePageImpressions';
        $others = ['dimensions' => 'ga:pagePath'];
        $analyticsData = Analytics::performQuery($period, $metrics, $others);
        $rows = $analyticsData->rows;
        //$rows = [['/pages/1/article-slug-1', '85', '1.10'], ['/pages/2/article-slug-2', '110', '2.5'], ['/categories/2/something', '10', '0.0']];

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

        return ['status' => 'success'];
    }
}
