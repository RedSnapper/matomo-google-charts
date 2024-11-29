<?php

namespace RedSnapper\MatomoCharts\Analytics\Models;

use Illuminate\Contracts\View\View;
use RedSnapper\MatomoCharts\Matomo\MatomoAPIInterface;
use Carbon\Carbon;
use RedSnapper\MatomoCharts\Analytics\DTO\AnalyticsChartItem;
use RedSnapper\MatomoCharts\Analytics\Enums\AnalyticsChartComponent;
use RedSnapper\MatomoCharts\Analytics\Enums\AnalyticsChartStyle;
use RedSnapper\MatomoCharts\Analytics\Enums\AnalyticsChartType;
use Illuminate\Support\Collection;

abstract class AnalyticsChart
{
    public Collection $data;
    public string $title;
    public array $chartData = [];
    public array $columns;
    public array $properties;
    public AnalyticsChartComponent $component;
    public AnalyticsChartType $type;
    public AnalyticsChartStyle $style;
    public array $options = [];
    public bool $printPageBreakAfter = false;
    public bool $appendHeadingsToData = true;
    public bool $includeSubItems = false;

    public function __construct(
        protected readonly int $matomoSiteId,
        protected readonly Carbon $startDate,
        protected readonly Carbon $endDate,
        protected readonly MatomoAPIInterface $matomoAPI
    ) {
        $this->query();

        if ($this->appendHeadingsToData) {
            $this->chartData[] = $this->columns;
        }

        $this->processChartData();
    }

    public function query(): void
    {
        $this->data = collect();
    }

    public function processChartData(): void
    {
        $this->data->each(function ($item) {
            // If the item is an array it means there is no data!
            if (is_array($item)) {
                abort(400, 'There was an error processing the required chart data.');
            }

            $childItems = $this->processSubTable($item);

            $data = [];

            collect($this->properties)->each(function ($prop) use (&$data, $item) {
                $data[] = $item->{$prop} ?? 0;
            });

            $this->chartData[] =
                new AnalyticsChartItem(data: $data, subItems: (empty($childItems) ? null : $childItems));
        });
    }

    private function processSubTable($item): array
    {
        $childItems = [];

        if (property_exists($item, 'subtable')) {
            collect($item->subtable)->each(function ($subitem) use (&$data, $item, &$childItems) {
                $data = [];

                collect($this->properties)->each(function ($prop) use (&$data, $item, $subitem) {
                    $data[] = $subitem->{$prop} ?? 0;
                });

                $subChildren = null;

                if (property_exists($subitem, 'subtable')) {
                    $subChildren = $this->processSubTable($subitem);
                }

                $childItems[] = new AnalyticsChartItem(data: $data, subItems: $subChildren);
            });
        }

        return $childItems;
    }

    abstract public function getTextView(): ?View;

    public function getText(): string
    {
        if ($view = $this->getTextView()) {
            return $view->render();
        }

        return '';
    }

    public function getChartData(): array
    {
        return collect($this->chartData)
            ->map(fn($dataItem) => ($dataItem instanceof AnalyticsChartItem ? $dataItem->toArray() : $dataItem))
            ->toArray();
    }
}
