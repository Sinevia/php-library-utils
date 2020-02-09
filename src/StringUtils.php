<?php

// ========================================================================= //
// SINEVIA PUBLIC                                        http://sinevia.com  //
// ------------------------------------------------------------------------- //
// COPYRIGHT (c) 2008-2020 Sinevia Ltd                   All rights resrved! //
// ------------------------------------------------------------------------- //
// LICENCE: All information contained herein is, and remains, property of    //
// Sinevia Ltd at all times.  Any intellectual and technical concepts        //
// are proprietary to Sinevia Ltd and may be covered by existing patents,    //
// patents in process, and are protected by trade secret or copyright law.   //
// Dissemination or reproduction of this information is strictly forbidden   //
// unless prior written permission is obtained from Sinevia Ltd per domain.  //
//===========================================================================//

namespace Sinevia;

class StringUtils {
    /**
     * Returns the substring between two matches
     * @return String|null the substring that was found, null otherwise
     */
    public static function between($string, $matchLeft, $matchRight) {
        $leftFrom = static::leftFrom($string,$matchRight);
        
        if ($leftFrom === null){
            return null;
        }

        $rightFrom = static::rightFrom($leftFrom,$matchLeft);

        if ($rightFrom === null){
            return null;
        }

        return $rightFrom;
    }

    public static function camelize($string, $separator = " ", $remove_separator = false) {
        $string = str_replace($separator, " ", $string);
        $ucstring = ucwords($string);
        if ($remove_separator) {
            $string = str_replace(" ", "", $ucstring);
        } else {
            $string = str_replace(" ", $separator, $ucstring);
        }
        return $string;
    }

    /**
     * Checks if a string ends with another string
     * $result = s_str_starts_with("http://server.com",".com");
     * // $result is true
     * </code>
     * @return bool true on success, false otherwise
     */
    public static function endsWith($string, $match) {
        return (substr($string, (strlen($string) - strlen($match)), strlen($match)) == $match) ? true : false;
    }

    /**
     * Fixes all new lines \r\n to become \n
     */
    public static function fixNewLines($text) {
        $text = str_replace("\r\n", "\n", $text); // replace \r\n to \n
        $text = str_replace("\r", "\n", $text);   // remove \rs
        return $text;
    }

    function fromCamelCase($input) {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }

    public static function hasMinumumChars($string, $chars) {
        if (strlen($string) >= $chars) {
            return true;
        }
        return false;
    }

    public static function hasLowercase($string) {
        if (preg_match("/[a-z]/", $string) === 0) {
            return FALSE;
        }
        return true;
    }


    public static function hasNumber($string) {
        if (preg_match("/[0-9]/", $string) === 0) {
            return false;
        }
        return true;
    }

    /**
     * Checks whether a string contains only characters specified in the gama.
     * @param StringUtils $string
     * @param StringUtils $gama
     * @return boolean
     */
    public static function hasOnly($string, $gama) {
        $chars = self::mb_str_to_array($string);
        $gama = self::mb_str_to_array($gama);
        foreach ($chars as $char) {
            if (in_array($char, $gama) == false)
                return false;
        }
        return true;
    }

    public static function hasSubstring($string, $substring) {
        return strpos($string, $substring) === false ? false : true;
    }

    public static function hasUppercase($string) {
        if (preg_match("/[A-Z]/", $string) === 0) {
            return FALSE;
        }
        return true;
    } 
    
    /**
     * Simple function to convert HTML email to text
     */
    public static function htmlEmailToText($html) {
        // 1. Fix new lines
        $html = str_replace("\r\n", "\n", $html); // replace \r\n to \n
        $html = str_replace("\r", "\n", $html); // remove \rs
        // 2. Regex strip tags
        $search = array(
            '@<head[^>]*?>.*?</head>@si', // Strip out head
            '@<script[^>]*?>.*?</script>@si', // Strip out javascript
            '@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags
            '@<style[^>]*?>.*?</style>@siU', // Strip style tags properly
            '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA
        );
        $text = preg_replace($search, '', $html);

        // 3. PHP strip tags
        $text = strip_tags($text);

        // 4. Remove leading and trailing spaces on each line
        $text = preg_replace("/[ \t]*\n[ \t]*/im", "\n", $text);

        // 5. Printable chars from entities
        $text = html_entity_decode($text);

        // 6. Remove multiple new lines
        $text = preg_replace("/\n\n\n\n/im", "\n", $text);
        $text = preg_replace("/\n\n\n/im", "\n", $text);
        $text = preg_replace("/\n\n/im", "\n", $text);
        $text = preg_replace("/\n\n/im", "\n", $text);

        // 7. Remove leading and trailing whitespace
        $text = trim($text);

        return $text;
    }

    /**
     * Checks if a string is email
     * @param String $email
     * @return boolean
     */
    public static function isEmail($email) {
        // Check for invalid characters
        if (preg_match('/[\x00-\x1F\x7F-\xFF]/', $email))
            return false;

        // Check that there's one @ symbol, and that the lengths are right
        if (!preg_match('/^[^@]{1,64}@[^@]{1,255}$/', $email))
            return false;

        // Split it into sections to make life easier
        $email_array = explode('@', $email);

        // Check local part
        $local_array = explode('.', $email_array[0]);
        foreach ($local_array as $local_part) {
            if (!preg_match('/^(([A-Za-z0-9!#$%&\'*+\/=?^_`{|}~-]+)|("[^"]+"))$/', $local_part))
                return false;
        }

        // Check domain part
        if (preg_match('/^(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])(\.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}$/', $email_array[1]) || preg_match('/^\[(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])(\.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}\]$/', $email_array[1])) {
            return true; // If an IP address
        } else { // If not an IP address
            $domain_array = explode('.', $email_array[1]);
            if (sizeof($domain_array) < 2)
                return false; // Not enough parts to be a valid domain

            foreach ($domain_array as $domain_part) {
                if (!preg_match('/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9])){2,6}$/', $domain_part))
                    return false;
            }

            return true;
        }
    }
    
    
    /**
     * Checks whether the string is a valid JSON
     * @return bool
     */
    public static function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }



    /**
     * Returns the substring on the LHS of a match
     * @return String|null the substring that was found, null otherwise
     */
    public static function leftFrom($string, $match) {
        $pos = strpos($string, $match);
        if ($pos === false) {
            return null;
        }
        return substr($string, 0, $pos);
    }

    /**
     * Returns the first $num words of $string
     */
    public static function maxWords($string, $num, $suffix = '') {
        $words = explode(' ', $string);
        if (count($words) < $num) {
            return $string;
        } else {
            return implode(' ', array_slice($words, 0, $num)) . $suffix;
        }
    }
    
    /**
     * Converts a well-formed string with <p> tags to string with <br /> tags
     * @param string $string
     * @return string
     */
    public static function p2br($string){
        $p2br = str_replace('</p><p>', '<br />', $string);
        $p2brNoEndParas = str_replace('</p>', '', $p2br);
        $noParas = str_replace('<p>', '', $p2brNoEndParas);
        return $noParas;
    }

    /**
     * Replaces a matching regex with match aware replacement string
     * <code>
     * regexReplace('/(pic)/','<a href="$1">$1</a>');
     * </code>
     */
    public static function regexReplace($string,$regex,$replacementWithMatches){
        return preg_replace($regex, $replacementWithMatches, $string); 
    }
    
    /**
     * Surrounds a matching regex with prefix and postfix string
     */
    public static function regexSurround($string,$regex,$prefix,$postfix){
        return preg_replace($regex, $prefix.'$1'.$postfix, $string); 
    }

    public static function snakify($string, $separator = " ", $remove_separator = false) {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $string, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        $string = implode(' ', $ret);

        if ($remove_separator) {
            $string = str_replace(" ", "", $string);
        } else {
            $string = str_replace(" ", $separator, $string);
        }
        return strtolower($string);
    }
    
    public static function substringBetween($string, $match_left, $match_right, $ignore_case = false) {
        $function = $ignore_case ? 'stripos' : 'strpos';

        $start = $function($string, $match_left);
        if ($start === false) {
            return false;
        }

        $start += strlen($match_left);
        $end = $function($string, $match_right, $start);
        if ($end === false) {
            return false;
        }

        return substr($string, $start, $end - $start);
    }

    /**
     * Returns a random string.
     *
     * The returnes string can be of specified length and specified
     * allowed characters.
     * <code>
     * $string = str_random(10,"");
     * // $result is true
     * </code>
     * @param $length int Integer specifying the desired returned length
     * @param $string String A string with the allowed characters
     * @return StringUtils
     */
    public static function random($length = 8, $string = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890") {
        $string = $string . $string . $string;
        $string = $string . $string . $string;
        $string = $string . $string . $string;
        $string = $string . $string . $string;
        
        $chars = str_split($string);
        shuffle($chars);
        shuffle($chars);
        return implode("", array_splice($chars, 0, $length));
    }

    /**
     * Returns the substring on the RHS of a match
     * @return String|null the substring that was found, null otherwise
     */
    public static function rightFrom($string, $match) {
        $pos = strpos($string, $match);

        if ($pos === false) {
            return null;
        }

        return substr($string, $pos + strlen($match));
    }

    /**
     *  Given a string such as "comment_123" or "id_57", it returns the
     *  final, numeric id.
     *  @return int
     */
    public static function splitId($string) {
        return preg_match('/[_-]([0-9]+)$/', $string, $matches[1]);
    }

    /**
     * Creates a friendly URL slug from a string
     */
    public static function slugify($string) {
        $string = preg_replace('/[^a-zA-Z0-9 -]/', '', $string);
        $string = trim($string);
        $string = str_replace("  ", " ", $string);
        $string = str_replace("  ", " ", $string);
        $string = str_replace("  ", " ", $string);
        $string = str_replace(' ', '-', $string);
        $string = preg_replace('/-+/', '-', $string);
        return strtolower($string);
    }

    /**
     * Checks if a string starts with another string.
     * <code>
     * $result = s_str_starts_with("http://server.com","http://");
     * // $result is true
     * </code>
     * @return bool true on success, false otherwise
     */
    public static function startsWith($string, $match) {
        return (substr($string, 0, strlen($match)) == $match) ? true : false;
    }

    /**
     * Splits a string by space
     */
    public static function toWords($string) {
//        $t= array(' ', "\t", '=', '+', '-', '*', '/', '\\', ',', '.', ';', ':', '[', ']', '{', '}', '(', ')', '<', '>', '&', '%', '$', '@', '#', '^', '!', '?', '~'); // separators
//        $string= str_replace($t, " ", $string);
        $words = explode(' ', $string);
        return $words;
    }

}
