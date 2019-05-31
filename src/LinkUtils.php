<?php

namespace Sinevia;

class LinkUtils {
    
    protected static function baseUrl() {
        return \Sinevia\Registry::get("URL_BASE", 'localhost');
    }

    public static function buildUrl($path, $queryData = []) {
        return self::baseUrl() . '/' . trim($path, '/') . self::query($queryData);
    }
  
    public static function query($queryData = []) {
        $queryString = '';
        if (count($queryData)) {
            $queryString = '?' . http_build_query($queryData);
        }
        return $queryString;
    }
}
