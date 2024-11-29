<?php

namespace RedSnapper\MatomoCharts\Analytics\DTO;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AnalyticsDateFilters
{
    public Carbon $startDate;

    public Carbon $endDate;

    public Carbon $preStartDate;

    public Carbon $preEndDate;

    public function __construct(Request $request)
    {
        $this->setDateFilters($request);
        $this->setPreDateFilters();
    }

    private function setDateFilters(Request $request): void
    {
        $this->startDate = ($request->get('dateFrom') !== null
            ? Carbon::parse($request->get('dateFrom'))
            : Carbon::now()->subMonth()->startOfMonth());

        $this->endDate = ($request->get('dateTo') !== null
            ? Carbon::parse($request->get('dateTo'))
            : Carbon::now()->subMonth()->endOfMonth());
    }

    private function setPreDateFilters(): void
    {
        $diff = $this->startDate->diffInDays($this->endDate);
        $this->preStartDate = $this->startDate->copy()->subDays($diff);
        $this->preEndDate = $this->preStartDate->copy()->addDays($diff);
    }
}