<?php

namespace Sinevia;

class LinkUtils {
    
    protected static function baseUrl() {
        return \Sinevia\Registry::get("URL_BASE", 'localhost');
    }

    public static function buildUrl($path, $queryData = []) {
        $url = self::baseUrl() . '/' . trim($path, '/') . self::query($queryData);

        if (StringUtils::startsWith($url, 'http://') == false and StringUtils::startsWith($url, 'https://') == false) {
            $serverProtocol = $_SERVER['SERVER_PROTOCOL'] ?? '';
            $protocol = stripos($serverProtocol, 'https') === 0 ? 'https://' : 'http://';
            $url = $protocol . $url;
        }

        return $url;
    }
  
    public static function query($queryData = []) {
        $queryString = '';
        if (count($queryData)) {
            $queryString = '?' . http_build_query($queryData);
        }
        return $queryString;
    }
}
