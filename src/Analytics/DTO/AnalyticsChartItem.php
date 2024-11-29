<?php

namespace RedSnapper\MatomoCharts\Analytics\DTO;

use Illuminate\Support\Collection;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use Spatie\DataTransferObject\DataTransferObject;

class AnalyticsChartItem extends DataTransferObject
{
    public array $data = [];

    #[CastWith(ArrayCaster::class, itemType: AnalyticsChartItem::class)]
    public ?Collection $subItems = null;

    public function toArray(): array
    {
        return $this->data;
    }
}
