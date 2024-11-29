<?php

return [

    'api_enabled'   => env('MATOMO_API_ENABLED', false),

    /*
     *  Server URL
     */
    'piwik_url'     => 'https://matomo.redsnapper.net',

    /*
     *  Optional API Key (will be used instead of Username and Password)
     *  The bundle works much faster with the API Key, rather than username and password.
     */

    'api_key'       => env('MATOMO_API_KEY'),

    /*
     *  Format for API calls to be returned in
     *
     *  Can be [php, xml, json, html, rss, original]
     *
     *  The default is 'json'
     */
    'format'        => 'json',

    /*
     *  Period/Date range for results
     *
     *  Can be [today, yesterday, previous7, previous30, last7, last30, currentweek, currentmonth, currentyear]
     *  as well as a date range in the format of "yyyy-MM-dd,yyyy-MM-dd"
     *
     *  The default is 'yesterday'
     */
    'period'        => 'last30',

    /*
     *  The Site ID you want to use
     */
    'site_id'       => env('MATOMO_SITE_ID'),

    /*
     * Indicates if cURL should verify the server certificate
     * Can be boolean or a path to a custom SSL certificate on disk
     */
    'verify_peer' => true,

    /*
     * HTTP timeout to be used when såending requests to the Piwik server. Defaults to 5 (seconds)
     */
    'http_timeout' => 500.0,
];
