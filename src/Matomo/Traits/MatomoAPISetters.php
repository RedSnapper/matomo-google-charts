<?php

namespace RedSnapper\MatomoCharts\Matomo\Traits;

use Illuminate\Support\Carbon;

trait MatomoAPISetters
{
    /**
     * Formats the passed start and end dates into a string that can be passed with API calls to search
     * a required timeframe
     *
     * @param \Carbon\Carbon|null $startDate
     * @param \Carbon\Carbon|null $endDate
     * @return $this
     */
    public function setDate(?\Carbon\Carbon $startDate = null, ?\Carbon\Carbon $endDate = null): static
    {
        if ($startDate === null || $endDate === null) {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        }

        $this->date = $startDate->format('Y-m-d') . ',' . $endDate->format('Y-m-d');

        return $this;
    }

    /**
     * Sets the segment value
     *
     * @param string $segment
     * @return $this
     */
    public function setSegment(string $segment): static
    {
        $this->segment = $segment;

        return $this;
    }

    /**
     * Sets the site ID to use
     *
     * @param int $siteId
     * @return $this
     */
    public function setSiteID(int $siteId): static
    {
        $this->siteId = $siteId;

        return $this;
    }
}
