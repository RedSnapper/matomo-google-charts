<?php

namespace RedSnapper\MatomoCharts\Matomo;

use RedSnapper\MatomoCharts\Matomo\Traits\MatomoAPICache;
use RedSnapper\MatomoCharts\Matomo\Traits\MatomoAPISetters;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use RedSnapper\MatomoCharts\Matomo\Collections\MatomoCollection;

class MatomoAPIFake implements MatomoAPIInterface
{
    use MatomoAPICache;
    use MatomoAPISetters;

    public string $date;
    public string $cacheName;
    public string $segment;
    public Collection $result;

    public function __construct()
    {
        $this->setDate();
        $this->segment = '';
        $this->result = collect();
    }

    public function get(): MatomoCollection
    {
        return new MatomoCollection($this->result);
    }

    public function fetchWeeklyData(): static
    {
        return $this;
    }

    public function fetchPageViewData(): static
    {
        return $this;
    }

    public function fetchCountryData(): static
    {
        return $this;
    }

    public function fetchBrowserData(): static
    {
        return $this;
    }

    public function fetchDeviceTypes(): static
    {
        return $this;
    }

    public function fetchOSVersions(): static
    {
        return $this;
    }

    public function fetchDailyVisitorData(Carbon $startDate): static
    {
        return $this;
    }

    public function fetchHourlyVisitorData(Carbon $startDate): static
    {
        return $this;
    }

    public function fetchWebsiteReferrerData(): static
    {
        return $this;
    }

    public function fetchSiteSearchKeywordData(): static
    {
        return $this;
    }

    public function fetchCampaignData(): static
    {
        return $this;
    }
}
