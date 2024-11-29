<?php

namespace RedSnapper\MatomoCharts\Matomo;

use Carbon\Carbon;
use RedSnapper\MatomoCharts\Matomo\Collections\MatomoCollection;

interface MatomoAPIInterface
{
    // Cache keys
    public const CACHE_KEY_WEEKLY_DATA = 'matomo_weekly_data';
    public const CACHE_KEY_PAGE_VIEW_DATA = 'matomo_page_view_data';
    public const CACHE_KEY_COUNTRY_DATA = 'matomo_country_data';
    public const CACHE_KEY_BROWSER_DATA = 'matomo_browser_data';
    public const CACHE_KEY_DEVICE_DATA = 'matomo_device_data';
    public const CACHE_KEY_OS_DATA = 'matomo_os_data';
    public const CACHE_KEY_DAILY_VISITOR_DATA = 'matomo_daily_visitor_data';
    public const CACHE_KEY_HOURLY_VISITOR_DATA = 'matomo_hourly_visitor_data';
    public const CACHE_KEY_WEBSITE_REFERRER_DATA = 'matomo_website_referrer_data';
    public const CACHE_KEY_SITE_SEARCH_KEYWORD_DATA = 'matomo_site_search_keyword_data';
    public const CACHE_KEY_SITE_CAMPAIGNS_DATA = 'matomo_site_campaigns_data';

    // API Fetch calls
    public function fetchWeeklyData(): static;
    public function fetchPageViewData(): static;
    public function fetchCountryData(): static;
    public function fetchBrowserData(): static;
    public function fetchDeviceTypes(): static;
    public function fetchOSVersions(): static;
    public function fetchDailyVisitorData(Carbon $startDate): static;
    public function fetchHourlyVisitorData(Carbon $startDate): static;
    public function fetchWebsiteReferrerData(): static;
    public function fetchSiteSearchKeywordData(): static;
    public function fetchCampaignData(): static;
    public function get(): MatomoCollection;

    // Setters
    public function setDate(?\Carbon\Carbon $startDate = null, ?\Carbon\Carbon $endDate = null): static;
    public function setSegment(string $segment): static;
    public function setSiteId(int $siteId): static;

    // Cache
    public function checkCacheAndSetResultIfFound(string $cacheKey): bool;
    public function storeResult($data): void;
}
