<?php

namespace RedSnapper\MatomoCharts\Analytics\Models;

use RedSnapper\MatomoCharts\Matomo\MatomoAPIInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;

abstract class AnalyticsMiniChart extends AnalyticsChart
{
    public Collection $preData;
    public bool $hasValueComparison = false;

    public const ICON_CALENDAR = 'calendar';
    public const ICON_GLOBE = 'globe';
    public const ICON_MONITOR = 'monitor';
    public const ICON_VIEWS = 'views';
    public const ICON_VISITORS = 'visitors';

    public function __construct(
        protected readonly int $matomoSiteId,
        protected readonly Carbon $startDate,
        protected readonly Carbon $endDate,
        protected readonly Carbon $preStartDate,
        protected readonly Carbon $preEndDate,
        protected readonly MatomoAPIInterface $matomoAPI
    ) {
        if ($this->appendHeadingsToData) {
            $this->chartData[] = $this->columns;
        }

        $this->query();

        $this->processChartData();
    }

    public function getComparisonDifference(): string
    {
        return '';
    }

    abstract public function getStatIcon(): ?string;

    public function renderStatIcon(): string
    {
        $icon = $this->getStatIcon() ?? self::ICON_VISITORS;

        return view('matomo-chart::icons.' . $icon)->render();
    }
}
