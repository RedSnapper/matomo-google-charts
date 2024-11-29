<?php

namespace RedSnapper\MatomoCharts\Analytics\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use RedSnapper\MatomoCharts\Analytics\Models\AnalyticsMiniChart;

/**
 * @mixin AnalyticsMiniChart
 */
class AnalyticsMiniChartResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->title,
            'type' => $this->type,
            'data' => $this->getChartData(),
            'options' => $this->options,
            'icon' => $this->renderStatIcon(),

            'comparison' => [
                'enabled' => $this->hasValueComparison,
                'value' => (is_numeric($this->getComparisonData()['value']) ? number_format($this->getComparisonData()['value']) : $this->getComparisonData()['value']),
                'preValue' => (is_numeric($this->getComparisonData()['preValue']) ? number_format($this->getComparisonData()['preValue']) : $this->getComparisonData()['preValue']),
                'difference' => (is_numeric($this->getComparisonDifference()) ? number_format($this->getComparisonDifference()) : $this->getComparisonDifference()),
                'label' => $this->getComparisonData()['label']
            ]
        ];
    }
}
