<?php

namespace RedSnapper\MatomoCharts\Matomo\Traits;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

trait MatomoAPICache
{
    /**
     * Gets the name of the function that was last called and then uses this to form a name that can be used
     * to identify the data within the cache store. The cache is then checked to see if data already exists,
     * if it does it will be set to the result property. Otherwise, return false.
     *
     * @param string $cacheKey
     * @return bool
     */
    public function checkCacheAndSetResultIfFound(string $cacheKey): bool
    {
        $this->cacheName = $cacheKey . '_' . $this->siteId . '_' . (isset($this->segment) ? str($this->segment)->limit() . '_' : '') . $this->date;

        if (Cache::has($this->cacheName)) {
            $this->result = collect(Cache::get($this->cacheName));
            return true;
        }

        return false;
    }

    /**
     * Takes data and stores it within the cache and sets it to the value of the result property
     *
     * @param $data
     * @return void
     */
    public function storeResult($data): void
    {
        Cache::put($this->cacheName, $data, Carbon::tomorrow()->startOfDay()); // Store until midnight

        $this->result = collect($data);
    }
}
