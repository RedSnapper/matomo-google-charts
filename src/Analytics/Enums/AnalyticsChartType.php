<?php

namespace RedSnapper\MatomoCharts\Analytics\Enums;

enum AnalyticsChartType: string
{
    case GEO = 'GeoChart';
    case COLUMN = 'ColumnChart';
    case LINE = 'LineChart';
    case PIE = 'PieChart';
    case STANDARD_TABLE = 'StandardTable';
}
