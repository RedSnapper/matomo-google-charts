<?php

namespace RedSnapper\MatomoCharts\Matomo;

use RedSnapper\MatomoCharts\Matomo\Traits\MatomoAPICache;
use RedSnapper\MatomoCharts\Matomo\Traits\MatomoAPISetters;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use RedSnapper\MatomoCharts\Matomo\Collections\MatomoCollection;
use RobBrazier\Piwik\Facades\Piwik as Matomo;

class MatomoAPI implements MatomoAPIInterface
{
    use MatomoAPICache;
    use MatomoAPISetters;

    public string $date;
    public string $cacheName;
    public string $segment;
    public string $siteId;
    public Collection $result;

    public function __construct()
    {
        $this->setDate();
        $this->segment = '';
        $this->siteId = config('piwik.site_id');
    }

    /**
     * Gets data split by week
     *
     * @return MatomoAPI
     */
    public function fetchWeeklyData(): static
    {
        if (!$this->checkCacheAndSetResultIfFound(self::CACHE_KEY_WEEKLY_DATA)) {
            $this->storeResult(
                Matomo::getAPI()->get([
                    'date' => $this->date,
                    'idSite' => $this->siteId,
                    'period' => 'week',
                    'format_metrics' => 0,
                    'expanded' => 1
                ])
            );
        }

        return $this;
    }

    /**
     * Gets data on each page for the site over the given time period
     *
     * @return MatomoAPI
     */
    public function fetchPageViewData(): static
    {
        if (!$this->checkCacheAndSetResultIfFound(self::CACHE_KEY_PAGE_VIEW_DATA)) {
            $this->storeResult(
                Matomo::getActions()->getPageUrls([
                    'date' => $this->date,
                    'idSite' => $this->siteId,
                    'period' => 'month',
                    'expanded' => 1
                ])
            );
        }

        return $this;
    }

    /**
     * Gets the visitor country data
     *
     * @return $this
     */
    public function fetchCountryData(): static
    {
        if (!$this->checkCacheAndSetResultIfFound(self::CACHE_KEY_COUNTRY_DATA)) {
            $this->storeResult(
                Matomo::getUserCountry()->getCountry([
                    'date' => $this->date,
                    'idSite' => $this->siteId,
                    'period' => 'month',
                    'expanded' => 1,
                    'segment' => $this->segment
                ])
            );
        }

        return $this;
    }

    /**
     * Gets the visitor browser data
     *
     * @return $this
     */
    public function fetchBrowserData(): static
    {
        if (!$this->checkCacheAndSetResultIfFound(self::CACHE_KEY_BROWSER_DATA)) {
            $this->storeResult(
                Matomo::getDevicesDetection()->getBrowsers([
                    'date' => $this->date,
                    'idSite' => $this->siteId,
                    'period' => 'month',
                    'expanded' => 1,
                ])
            );
        }

        return $this;
    }

    /**
     * Gets the visitor device type data
     *
     * @return $this
     */
    public function fetchDeviceTypes(): static
    {
        if (!$this->checkCacheAndSetResultIfFound(self::CACHE_KEY_DEVICE_DATA)) {
            $this->storeResult(
                Matomo::getDevicesDetection()->getType([
                    'date' => $this->date,
                    'idSite' => $this->siteId,
                    'period' => 'month',
                    'expanded' => 1,
                ])
            );
        }

        return $this;
    }

    /**
     * Gets the visitor OS version data
     *
     * @return $this
     */
    public function fetchOSVersions(): static
    {
        if (!$this->checkCacheAndSetResultIfFound(self::CACHE_KEY_OS_DATA)) {
            $this->storeResult(
                Matomo::getDevicesDetection()->getOsVersions([
                    'date' => $this->date,
                    'idSite' => $this->siteId,
                    'period' => 'month',
                    'expanded' => 1,
                ])
            );
        }

        return $this;
    }

    /**
     * Gets the daily visitor data
     *
     * @return $this
     */
    public function fetchDailyVisitorData(Carbon $startDate): static
    {
        if (!$this->checkCacheAndSetResultIfFound(self::CACHE_KEY_DAILY_VISITOR_DATA)) {
            $this->storeResult(
                Http::get(config('piwik.piwik_url') . '/index.php?module=API&format=' . config('piwik.format') . '&idSite=' . $this->siteId . '&period=month&date=' . $startDate->format('Y-m-d') . '&method=VisitTime.getByDayOfWeek&filter_limit=100&format_metrics=1&expanded=1&token_auth=' . config('piwik.api_key'))->object()
            );
        }

        return $this;
    }

    /**
     * Gets the hourly visitor data
     *
     * @return $this
     */
    public function fetchHourlyVisitorData(Carbon $startDate): static
    {
        if (!$this->checkCacheAndSetResultIfFound(self::CACHE_KEY_HOURLY_VISITOR_DATA)) {
            $this->storeResult(
                Http::get(config('piwik.piwik_url') . '/index.php?module=API&format=' . config('piwik.format') . '&idSite=' . $this->siteId . '&period=month&date=' . $startDate->format('Y-m-d') . '&method=VisitTime.getVisitInformationPerServerTime&filter_limit=100&format_metrics=1&expanded=1&token_auth=' . config('piwik.api_key'))->object()
            );
        }

        return $this;
    }

    /**
     * Gets the referrer website data
     *
     * @return $this
     */
    public function fetchWebsiteReferrerData(): static
    {
        if (!$this->checkCacheAndSetResultIfFound(self::CACHE_KEY_WEBSITE_REFERRER_DATA)) {
            $this->storeResult(
                Matomo::getReferrers()->getWebsites([
                    'date' => $this->date,
                    'idSite' => $this->siteId,
                    'period' => 'month',
                    'expanded' => 1,
                ])
            );
        }

        return $this;
    }

    /**
     * Gets the site search keywords data
     *
     * @return $this
     */
    public function fetchSiteSearchKeywordData(): static
    {
        if (!$this->checkCacheAndSetResultIfFound(self::CACHE_KEY_SITE_SEARCH_KEYWORD_DATA)) {
            $this->storeResult(
                Matomo::getActions()->getSiteSearchKeywords([
                    'date' => $this->date,
                    'idSite' => $this->siteId,
                    'period' => 'month',
                    'expanded' => 1,
                ])
            );
        }

        return $this;
    }

    public function fetchCampaignData(): static
    {
        if (!$this->checkCacheAndSetResultIfFound(self::CACHE_KEY_SITE_CAMPAIGNS_DATA)) {
            $this->storeResult(
                Matomo::getReferrers()->getCampaigns([
                    'date' => $this->date,
                    'idSite' => $this->siteId,
                    'period' => 'month',
                    'expanded' => 1,
                ])
            );
        }

        return $this;
    }

    /**
     * Returns the current result value as a collection
     *
     * @return MatomoCollection
     */
    public function get(): MatomoCollection
    {
        $this->segment = '';

        return new MatomoCollection($this->result);
    }
}
