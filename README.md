[![Gitpod Ready-to-Code](https://img.shields.io/badge/Gitpod-Ready--to--Code-blue?logo=gitpod)](https://gitpod.io/#https://github.com/Sinevia/php-library-utils) 

# PHP Library Utils

The utility library provides multiple helper methods for PHP. These include methods for working with CSV, data, files, strings, etc.

![No Dependencies](https://img.shields.io/badge/no-dependencies-success.svg)
![Tests](https://github.com/Sinevia/php-library-utils/workflows/Tests/badge.svg)
[![Gitpod Ready-to-Code](https://img.shields.io/badge/Gitpod-Ready--to--Code-blue?logo=gitpod)](https://gitpod.io/#https://github.com/sinevia/php-library-utils) 

## Installation ##

### 1. Via Composer ###

```
composer require sinevia/php-library-utils
```

### 2. Manually ###

Download from https://github.com/Sinevia/php-library-utils

## Usage ##

```php
\Sinevia\Utils::raiseMemoryLimits();
```

## Classes and methods ##

### Array Utils ###

- columns splits the array into columns
```php
\Sinevia\ArrayUtils::columns($array);
```

- isAssoc checks whether an array is associative
```php
\Sinevia\ArrayUtils::isAssoc($array);
```

- toCsv converts an array to CSV. If the array is associative, the keys will be used for a header row
```php
\Sinevia\ArrayUtils::toCsv($array, $forceQuotes=true);
```

### Browser Utils ###

- fingerprint
```php
\Sinevia\BrowserUtils::fingerprint();
```


### Csv Utils ###

- write
```php
\Sinevia\CsvUtils::write($filename, $array);
```

### Data Utils ###

- serialize
```php
\Sinevia\DataUtils::serialize($data, $pass);
```

- unserialize
```php
\Sinevia\DataUtils::unserialize($data, $pass);
```

### File Utils ###

### Link Utils ###
- getBaseUrl
```php
\Sinevia\LinkUtils::getBaseUrl(); // ""
```

- setBaseUrl
```php
\Sinevia\LinkUtils::setBaseUrl("https://yahoo.com"); // ""
```

- buildUrl
```php
\Sinevia\LinkUtils::buildUrl("/", ['a'=>'A', 'b'=>'B']); // "/?a=A&b=B"
```

### String Utils ###
- between
```php
\Sinevia\StringUtils::between("ABCDEFG","B","E"); // "CD"
```
- camelize
- endsWith
- fixNewLines - fixes \r\n to \n
- hasMinumumChars
- hasLowercase
- hasNumber
- hasOnly
- hasSubstring
- hasUppercase
- htmlEmailToText
- isEmail
```php
\Sinevia\StringUtils::isEmail("test@test"); // false
```
- isJson
```php
\Sinevia\StringUtils::isJson("ABC"); // false
```
- leftFrom
- maxWords
- p2br
- random
```php
\Sinevia\StringUtils::rand(8); // "aBDhkDyD"
\Sinevia\StringUtils::rand(8, "ABC"); // "BABCCB"
```
- regexSurround
- regexReplace
- rightFrom
- slugify
- snakify
- splitId
- startsWith
- substringBetween
- toArray
```php
\Sinevia\StringUtils::toArray("ABC"); // ["A", "B", "C"]
```
- toWords

### Utils ###
- arrayValueDelete
- colorNameToHex
- colorBrightness
- forceFileDownload
- forceFileDownloadFromContent
- getTimeAgo
- ip
```php
\Sinevia\Utils::ip();
```
- isCli
- pagination
- paginationReversed
- pathToUrl
- percents
- redirectAndPostData
- redirect
- raiseMemoryLimits
- rssToArray
