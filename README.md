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

- columns
```php
\Sinevia\ArrayUtils::columns();
```

### Browser Utils ###

- fingerprint
```php
\Sinevia\BrowserUtils::fingerprint();
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

### String Utils ###
- between
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
- isJson
- leftFrom
- p2br
- random
- regexSurround
- regexReplace
- rightFrom
- slugify
- snakify
- splitId
- startsWith
- substringBetween
- toWords

### Utils ###
- ip
```php
\Sinevia\Utils::ip();
```