<?php

namespace RedSnapper\MatomoCharts;

use Illuminate\Support\ServiceProvider;
use RedSnapper\MatomoCharts\Analytics\DTO\AnalyticsDateFilters;
use RedSnapper\MatomoCharts\Analytics\MatomoChart;
use RedSnapper\MatomoCharts\Matomo\MatomoAPI;
use RedSnapper\MatomoCharts\Matomo\MatomoAPIFake;
use RedSnapper\MatomoCharts\Matomo\MatomoAPIInterface;

class MatomoChartServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('matomo-chart', function ($app) {
            return new MatomoChart(
                $app->make(AnalyticsDateFilters::class),
                $app->make(MatomoAPIInterface::class)
            );
        });

        $this->app->singleton(MatomoAPIInterface::class, function () {
            if (config('piwik.api_enabled')) {
                return app()->make(MatomoAPI::class);
            }

            return app()->make(MatomoAPIFake::class);
        });

        $this->mergeConfigFrom(__DIR__.'/../config/piwik.php', 'matomo-chart');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/piwik.php' => config_path('piwik.php'),
        ], 'config');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'matomo-chart');
    }
}