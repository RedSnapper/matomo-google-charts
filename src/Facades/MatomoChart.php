<?php

namespace RedSnapper\MatomoCharts\Facades;

use Illuminate\Support\Facades\Facade;
use RedSnapper\MatomoCharts\Analytics\Models\AnalyticsChart;
use RedSnapper\MatomoCharts\Analytics\Models\AnalyticsMiniChart;
use RedSnapper\MatomoCharts\Analytics\Resources\AnalyticsChartResource;
use RedSnapper\MatomoCharts\Analytics\Resources\AnalyticsMiniChartResource;

/**
 * @see \RedSnapper\MatomoCharts\Analytics\MatomoChart
 * @method static AnalyticsChart makeChart(string $chartClass, int $matomoSiteId)
 * @method static AnalyticsChartResource makeChartResource(string $chartClass, int $matomoSiteId)
 * @method static AnalyticsMiniChart makeMiniChart(string $miniChartClass, int $matomoSiteId)
 * @method static AnalyticsMiniChartResource makeMiniChartResource(string $miniChartClass, int $matomoSiteId)
 */
class MatomoChart extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'matomo-chart';
    }
}