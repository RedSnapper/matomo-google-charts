<?php

namespace RedSnapper\MatomoCharts\Analytics\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use RedSnapper\MatomoCharts\Analytics\Models\AnalyticsChart;

/**
 * @mixin AnalyticsChart
 */
class AnalyticsChartResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'style' => $this->style,
            'title' => $this->title,
            'text' => $this->getText(),
            'component' => $this->component,
            'type' => $this->type,
            'data' => $this->getChartData(),
            'columns' => $this->columns,
            'options' => $this->options,
            'pageBreakAfter' => $this->printPageBreakAfter
        ];
    }
}
