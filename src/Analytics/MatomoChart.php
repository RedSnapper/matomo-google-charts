<?php

namespace RedSnapper\MatomoCharts\Analytics;

use RedSnapper\MatomoCharts\Analytics\DTO\AnalyticsDateFilters;
use RedSnapper\MatomoCharts\Analytics\Models\AnalyticsChart;
use RedSnapper\MatomoCharts\Analytics\Models\AnalyticsMiniChart;
use RedSnapper\MatomoCharts\Analytics\Resources\AnalyticsChartResource;
use RedSnapper\MatomoCharts\Analytics\Resources\AnalyticsMiniChartResource;
use RedSnapper\MatomoCharts\Matomo\MatomoAPIInterface;

class MatomoChart
{
    public function __construct(
        private readonly AnalyticsDateFilters $dateFilters,
        private readonly MatomoAPIInterface $matomoAPI
    ) {
    }

    public function makeChart(string $chartClass, int $matomoSiteId): AnalyticsChart
    {
        return new $chartClass(
            $matomoSiteId,
            $this->dateFilters->startDate,
            $this->dateFilters->endDate,
            $this->matomoAPI
        );
    }

    public function makeChartResource(string $chartClass, int $matomoSiteId): AnalyticsChartResource
    {
        return new AnalyticsChartResource($this->makeChart($chartClass, $matomoSiteId));
    }

    public function makeMiniChart(string $miniChartClass, int $matomoSiteId): AnalyticsMiniChart
    {
        return new $miniChartClass(
            $matomoSiteId,
            $this->dateFilters->startDate,
            $this->dateFilters->endDate,
            $this->dateFilters->preStartDate,
            $this->dateFilters->preEndDate,
            $this->matomoAPI
        );
    }

    public function makeMiniChartResource(string $miniChartClass, int $matomoSiteId): AnalyticsMiniChartResource
    {
        return new AnalyticsMiniChartResource($this->makeMiniChart($miniChartClass, $matomoSiteId));
    }
}