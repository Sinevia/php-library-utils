<?php

namespace Sinevia;

class LinkUtils {
    protected static $baseUrl = "";
    
    public static function getBaseUrl() {
        return self::$baseUrl;
    }

    public static function setBaseUrl($baseUrl) {
        return self::$baseUrl = $baseUrl;
    }

    public static function buildUrl($path, $queryData = []) {
        $isUrl = (StringUtils::startsWith($path, 'http://') or StringUtils::startsWith($path, 'https://'));
        
        if ($isUrl == false) {
            $url = self::getBaseUrl() . '/' . trim($path, '/');
            $url = ($url!='/') ? \rtrim($url,'/') : '/';
            if(self::getBaseUrl() != ''){
                if (StringUtils::startsWith(self::getBaseUrl(), 'http://') == false and StringUtils::startsWith(self::getBaseUrl(), 'https://') == false) {
                    $serverProtocol = $_SERVER['SERVER_PROTOCOL'] ?? '';
                    $protocol = stripos($serverProtocol, 'https') === 0 ? 'https://' : 'http://';
                    $url = $protocol . $url;
                }
            }
        } else {
            $url = $path;
        }

        $queryString = http_build_query($queryData);

        if ($queryString == "") {
            return $url;
        }

        $separator = (strpos($url, '?') === false) ? '?' : '&';

        $url = $url . $separator . $queryString;

        return $url;

        //     $protocol = stripos($serverProtocol, 'https') === 0 ? 'https://' : 'http://';

        // $url = self::getBaseUrl() . '/' . trim($path, '/') . self::query($queryData);

        // if (StringUtils::startsWith($url, 'http://') == false and StringUtils::startsWith($url, 'https://') == false) {
        //     $serverProtocol = $_SERVER['SERVER_PROTOCOL'] ?? '';
        //     $protocol = stripos($serverProtocol, 'https') === 0 ? 'https://' : 'http://';
        //     $url = $protocol . $url;
        // }

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
