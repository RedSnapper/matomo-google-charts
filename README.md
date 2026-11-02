# Matomo Google Charts

A Laravel package for building analytics dashboards using data from [Matomo](https://matomo.org/) and [Google Charts](https://developers.google.com/chart). It fetches analytics data from a Matomo instance, caches it, and transforms it into Google Charts-compatible data structures that can be served as JSON API resources.

## Requirements

- PHP 8.3+
- Laravel 10+

## Installation

```bash
composer require rs/matomo-google-charts
```

The package uses Laravel's auto-discovery, so the service provider and facade are registered automatically.

### Publish the config

```bash
php artisan vendor:publish --provider="RedSnapper\MatomoCharts\MatomoChartServiceProvider" --tag=config
```

This publishes `config/piwik.php`.

### Environment variables

Add the following to your `.env` file:

```env
MATOMO_API_ENABLED=true
MATOMO_API_KEY=your-matomo-token
MATOMO_SITE_ID=1
```

When `MATOMO_API_ENABLED` is `false` (the default), the package uses a fake API implementation that returns empty data, allowing you to develop without a live Matomo connection.

### Configuration

The published `config/piwik.php` file contains all available options:

| Key | Description | Default |
|---|---|---|
| `api_enabled` | Enable live Matomo API calls | `false` |
| `piwik_url` | Matomo server URL | `https://matomo.redsnapper.net` |
| `api_key` | Matomo authentication token | `null` |
| `format` | API response format (`json`, `xml`, `php`, etc.) | `json` |
| `period` | Default period for API queries | `last30` |
| `site_id` | Default Matomo site ID | `null` |
| `verify_peer` | SSL certificate verification | `true` |
| `http_timeout` | HTTP request timeout (seconds) | `500.0` |

## Usage

### Creating chart types

Define your chart by extending `AnalyticsChart`. Set the chart metadata as properties and override `query()` to fetch data from the Matomo API:

```php
use RedSnapper\MatomoCharts\Analytics\Models\AnalyticsChart;
use RedSnapper\MatomoCharts\Analytics\Enums\AnalyticsChartComponent;
use RedSnapper\MatomoCharts\Analytics\Enums\AnalyticsChartStyle;
use RedSnapper\MatomoCharts\Analytics\Enums\AnalyticsChartType;

class BrowserChart extends AnalyticsChart
{
    public string $title = 'Browsers';
    public array $columns = ['Browser', 'Visits'];
    public array $properties = ['label', 'nb_visits'];
    public AnalyticsChartComponent $component = AnalyticsChartComponent::GOOGLE_CHART;
    public AnalyticsChartType $type = AnalyticsChartType::PIE;
    public AnalyticsChartStyle $style = AnalyticsChartStyle::STANDARD;

    public function query(): void
    {
        $this->matomoAPI
            ->setSiteId($this->matomoSiteId)
            ->setDate($this->startDate, $this->endDate);

        $this->data = $this->matomoAPI->fetchBrowserData()->get();
    }

    public function getTextView(): ?View
    {
        return null;
    }
}
```

The `$properties` array maps Matomo response fields to chart columns. The `$columns` array provides the column headings. The base class automatically processes the raw Matomo data into a format compatible with Google Charts.

### Creating mini charts (with comparison)

Mini charts show a metric with a comparison against a previous period. Extend `AnalyticsMiniChart`:

```php
use RedSnapper\MatomoCharts\Analytics\Models\AnalyticsMiniChart;
use RedSnapper\MatomoCharts\Analytics\Enums\AnalyticsChartComponent;
use RedSnapper\MatomoCharts\Analytics\Enums\AnalyticsChartStyle;
use RedSnapper\MatomoCharts\Analytics\Enums\AnalyticsChartType;

class VisitorsMiniChart extends AnalyticsMiniChart
{
    public string $title = 'Total Visitors';
    public array $columns = ['Week', 'Visitors'];
    public array $properties = ['label', 'nb_uniq_visitors'];
    public AnalyticsChartComponent $component = AnalyticsChartComponent::GOOGLE_CHART;
    public AnalyticsChartType $type = AnalyticsChartType::LINE;
    public AnalyticsChartStyle $style = AnalyticsChartStyle::MINI;
    public bool $hasValueComparison = true;

    public function query(): void
    {
        $this->matomoAPI
            ->setSiteId($this->matomoSiteId)
            ->setDate($this->startDate, $this->endDate);

        $this->data = $this->matomoAPI->fetchWeeklyData()->get();

        // Fetch previous period data for comparison
        $this->matomoAPI->setDate($this->preStartDate, $this->preEndDate);
        $this->preData = $this->matomoAPI->fetchWeeklyData()->get();
    }

    public function getTextView(): ?View
    {
        return null;
    }

    public function getStatIcon(): ?string
    {
        return self::ICON_VISITORS;
    }

    public function getComparisonData(): array
    {
        return [
            'value' => $this->data->sum('nb_uniq_visitors'),
            'preValue' => $this->preData->sum('nb_uniq_visitors'),
            'label' => 'visitors',
        ];
    }
}
```

Available icons: `calendar`, `globe`, `monitor`, `views`, `visitors`.

### Using the facade

Use the `MatomoChart` facade to create charts and JSON resources in your controllers:

```php
use RedSnapper\MatomoCharts\Facades\MatomoChart;

// Get a chart model instance
$chart = MatomoChart::makeChart(BrowserChart::class, $matomoSiteId);

// Get a JSON resource (for API responses)
$resource = MatomoChart::makeChartResource(BrowserChart::class, $matomoSiteId);

// Mini charts
$miniChart = MatomoChart::makeMiniChart(VisitorsMiniChart::class, $matomoSiteId);
$miniResource = MatomoChart::makeMiniChartResource(VisitorsMiniChart::class, $matomoSiteId);
```

### Date filtering

Date ranges are automatically resolved from the HTTP request query parameters:

| Parameter | Description | Default |
|---|---|---|
| `dateFrom` | Start date (any Carbon-parseable string) | Start of previous month |
| `dateTo` | End date (any Carbon-parseable string) | End of previous month |

A comparison period of equal length is automatically computed from the primary date range.

### Chart resource JSON structure

`AnalyticsChartResource` returns:

```json
{
    "style": "standard",
    "title": "Browsers",
    "text": "",
    "component": "GoogleChart",
    "type": "PieChart",
    "data": [
        ["Browser", "Visits"],
        ["Chrome", 1200],
        ["Firefox", 800]
    ],
    "columns": ["Browser", "Visits"],
    "options": {},
    "pageBreakAfter": false
}
```

`AnalyticsMiniChartResource` additionally includes:

```json
{
    "comparison": {
        "enabled": true,
        "value": "1,500",
        "preValue": "1,200",
        "difference": "300",
        "label": "visitors"
    },
    "icon": "<svg>...</svg>"
}
```

### Available chart types

| Enum | Google Charts type |
|---|---|
| `AnalyticsChartType::GEO` | GeoChart |
| `AnalyticsChartType::COLUMN` | ColumnChart |
| `AnalyticsChartType::LINE` | LineChart |
| `AnalyticsChartType::PIE` | PieChart |
| `AnalyticsChartType::STANDARD_TABLE` | StandardTable |

### Available Matomo data fetchers

The `MatomoAPIInterface` provides methods you can use in your chart's `query()` method:

- `fetchWeeklyData()` - Weekly aggregated metrics
- `fetchPageViewData()` - Page URL data
- `fetchCountryData()` - Visitor countries
- `fetchBrowserData()` - Browser breakdown
- `fetchDeviceTypes()` - Device type breakdown
- `fetchOSVersions()` - OS version breakdown
- `fetchDailyVisitorData(Carbon $startDate)` - Visits by day of week
- `fetchHourlyVisitorData(Carbon $startDate)` - Visits by hour
- `fetchWebsiteReferrerData()` - Referring websites
- `fetchSiteSearchKeywordData()` - Site search keywords
- `fetchCampaignData()` - Campaign referral data

All fetch methods are chainable and return a `MatomoCollection` (extended Laravel Collection) via `->get()`.

### Caching

API responses are automatically cached until midnight using Laravel's cache system. Cache keys are scoped by site ID, segment, and date range to avoid collisions.

## Testing

```bash
composer test
```

## License

MIT
