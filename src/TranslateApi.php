<?php namespace iscms\translate;

interface TranslateApi
{
    public function translate($query, $from, $to);

    public function buildSign($query, $appID, $salt, $secKey);

    public function callOnce($url, $args = null, $method = "post", $withCookie = false, $timeout = 30, $headers = array());

    public function convert(&$args);
}