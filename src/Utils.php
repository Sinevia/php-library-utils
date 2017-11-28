<?php

// ========================================================================= //
// SINEVIA PUBLIC                                        http://sinevia.com  //
// ------------------------------------------------------------------------- //
// COPYRIGHT (c) 2008-2016 Sinevia Ltd                   All rights reserved //
// ------------------------------------------------------------------------- //
// LICENCE: All information contained herein is, and remains, property of    //
// Sinevia Ltd at all times.  Any intellectual and technical concepts        //
// are proprietary to Sinevia Ltd and may be covered by existing patents,    //
// patents in process, and are protected by trade secret or copyright law.   //
// Dissemination or reproduction of this information is strictly forbidden   //
// unless prior written permission is obtained from Sinevia Ltd per domain.  //
//===========================================================================//

namespace Sinevia;

/**
 * Class with convenience functions that do belong anywhere else
 * @version 2.0
 */
class Utils {

    /**
     * Prints to the screen strings,arrays and objects.
     * The printing is in easy to read manner, making the function
     * especially useful in urgent and fast need of debug.
     * <code>
     *    $names = array("Peter","Dan");
     *    \Sinevia\Utils::alert($names); // Prints the array to the screen
     * </code>
     * @param mixed String, Arrays, Boolean, Objects
     * @return void
     * @tested true
     */
    public static function alert($v) {
        $alert = "";
        if (is_array($v)) {
            $alert .= "<pre>";
            $alert .= print_r($v, true);
            $alert .= "</pre>";
        } elseif (is_null($v)) {
            echo 'NULL';
        } elseif (is_bool($v)) {
            echo ($v === false) ? 'FALSE' : 'TRUE';
        } elseif (is_object($v)) {
            $alert .= "<pre>";
            $alert .= var_dump($v, true);
            $alert .= "</pre>";
        } else {
            $alert = $v;
        }
        echo $alert . "<br />";
    }

    /**
     * Deletes an element in an array, with the given key (if exists)
     * and reduces the size of the array.
     * <code>
     *     $array = array("key1"=>"value1","key2"=>"value2");
     *     $array = \Sinevia\Utils::arrayKeyDelete($array,"key1");
     * </code>
     * @param Array the array, whose key is to be deleted
     * @param String the key to be deleted
     * @return array the resulting array
     * @tested true
     */
    public static function arrayKeyDelete($array, $key) {
        $key_index = array_keys(array_keys($array), $key);
        if (count($key_index) != '') {
            array_splice($array, $key_index[0], 1);
        }
        return $array;
    }

    /**
     * Splits an Array to columns. Convenient when we want to show columns
     * or rows next to each other on the website. The example shows how to
     * simply build columns display.
     * <code>
     *     $array = array("key1"=>"value1", "key2"=>"value2",
     *                    "key3"=>"value3", "key4"=>"value4");
     *     $columns = \Sinevia\Utils::arrayToColumns($array,2);
     *
     *     // Columns display
     *     echo "<table><tr>";
     *     for($i<0;$i<count($columns);$i++){
     *         foreach($column as $item){echo "<td>".$item."</td>";}
     *     }
     *     echo "<tr></table>";
     *
     *     // Columns display
     *     $rows = $colimn; // Just for demo
     *     echo "<table>";
     *     for($i<0;$i<count($rows);$i++){
     *         echo "<tr>";
     *         foreach($row as $item){echo "<td>".$item."</td>";}
     *         echo "</tr>";
     *     }
     *     echo "</table>";
     * </code>
     * @param Array the array, whic is to be split to columns
     * @param int number of columns needed
     * @return Array the column array
     * @tested not/some modifications at 3.0
     */
    public static function arrayToColumns($array, $columns) {
        $overflow = count($array) % $columns;
        $up_items = ceil(count($array) / $columns);
        $down_items = floor(count($array) / $columns);
        $pointer = 0;
        for ($column = 0; $column < $columns; $column++) {
            if ($column < $overflow) {
                $outarray[$column] = array_slice($array, $pointer, $up_items);
                $pointer += $up_items;
            } else {
                $outarray[$column] = array_slice($array, $pointer, $down_items);
                $pointer += $down_items;
            }
        }
        return $outarray;
    }

    /**
     * Creates a CSV formatted text from array
     * @param array $array
     * @return text
     */
    public static function arrayToCsv($array) {
        ob_start();
        $fp = fopen('php://output', 'w');
        foreach ($array as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);
        return ob_get_clean();
    }

    /**
     * Deletes an element from an array, with the given value (if exists)
     * and reduces the size of the array.
     * <code>
     *     $array = array("key1"=>"value1","key2"=>"value2");
     *     $array = \Sinevia\Utils::arrayValueDelete($array,"value2");
     * </code>
     * @param Array the array, whose key is to be deleted
     * @param String the key to be deleted
     * @return array the resulting array
     * @tested true
     */
    public static function arrayValueDelete($array, $value) {
        $value_index = array_keys(array_values($array), $value);
        if (count($value_index) != '') {
            array_splice($array, $value_index[0], 1);
        }
        return $array;
    }

    /**
     * Serializes given data.
     * @param mix $data
     * @return String
     */
    public static function dataSerialize($data) {
        $serialized = serialize($data);
        $encoded = self::xorEncode($serialized, "!some_@non_#sense_*string");
        $base64ed = base64_encode($encoded);
        return $base64ed;
    }

    /**
     * Unserializes data.
     * @param String $base64ed
     * @return mix
     */
    public static function dataUnserialize($base64ed) {
        $encoded = base64_decode($base64ed);
        if ($encoded === false) {
            return false;
        }
        $decoded = self::xorDecode($encoded, "!some_@non_#sense_*string");
        $data = unserialize($decoded);
        return $data;
    }
    
    /**
     * Returns the time in human readable format
     */
    public static function getTimeAgo($distant_timestamp, $max_units = 3, $postfix = " ago") {
        $i = 0;
        $time = time() - $distant_timestamp; // to get the time since that moment
        $tokens = [
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        ];

        $responses = [];
        while ($i < $max_units) {
            foreach ($tokens as $unit => $text) {
                if ($time < $unit) {
                    continue;
                }
                $i++;
                $numberOfUnits = floor($time / $unit);

                $responses[] = $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
                $time -= ($unit * $numberOfUnits);
                break;
            }
        }

        if (!empty($responses)) {
            return implode(', ', $responses) . $postfix;
        }

        return 'Just now';
    }

    /**
     * Modified to send mail to multiple recepients.
     * @param $to
     * @param $subject
     * @param $msg
     * @param $from
     */
    public static function mail($to, $subject, $msg, $options = array()) {
        if (is_array($to) == false) {
            $to = array($to);
        }
        $result = true;
        foreach ($to as $address) {
            $headers = array();
            $headers[] = "MIME-Version: 1.0";
            $headers[] = "X-Priority: 1";
            $headers[] = "X-MSMail-Priority: High";
            $headers[] = "X-Mailer: php";
            if (isset($options['cc']))
                $headers[] = "cc: " . $options['cc'];
            if (isset($options['bcc']))
                $headers[] = "Bcc: " . $options['bcc'];
            if (isset($options['from']))
                $headers[] = "From: " . $options['from'];
            if (isset($options['replyto'])) {
                $headers .= "Reply-To:  " . $options['replyto'];
            } else if (isset($options['from'])) {
                $headers .= "Reply-To:  " . $options['from'];
            }
            if (isset($options['encoding'])) {
                $headers[] = "Content-type: text/html; charset=" . $options['encoding'];
            }
            $headers = implode("\r\n", $headers);

            if (mail($address, $subject, $msg, $headers) == false) {
                $result = false;
            }
        }
        return $result;
    }

    //=====================================================================//
    //  METHOD: mail                                                       //
    //========================== END OF METHOD ============================//
    //========================= START OF METHOD ===========================//
    // METHOD: mail_html                                                   //
    //=====================================================================//
    /**
     * Sends an HTML mail
     * true or false
     * @param $to
     * @param $subject
     * @param $msg
     * @param $from
     * @param $plaintext
     */
    public static function mail_html($to, $subject, $msg, $from, $plaintext = '') {
        if (is_array($to) == false) {
            $to = array($to);
        }
        $result = true;
        foreach ($to as $address) {
            $boundary = uniqid(rand(), true);

            $headers = "From: $from\n";
            $headers .= "MIME-Version: 1.0\n";
            $headers .= "Content-Type: multipart/alternative; boundary = $boundary\n";
            $headers .= "This is a MIME encoded message.\n\n";
            $headers .= "--$boundary\n" .
                    "Content-Type: text/plain; charset=ISO-8859-1\n" .
                    "Content-Transfer-Encoding: base64\n\n";
            $headers .= chunk_split(base64_encode($plaintext));
            $headers .= "--$boundary\n" .
                    "Content-Type: text/html; charset=ISO-8859-1\n" .
                    "Content-Transfer-Encoding: base64\n\n";
            $headers .= chunk_split(base64_encode($msg));
            $headers .= "--$boundary--\n";

            if (mail($address, $subject, '', $headers) == false) {
                $result = false;
            }
        }
        return $result;
    }

    /**
     * A substitution of str_split working with not only ASCII strings.
     * @param String $string
     * @return Array
     */
    public static function multibyteStringToArray($string) {
        mb_internal_encoding("UTF-8"); // Important
        $chars = array();
        for ($i = 0; $i < mb_strlen($string); $i++) {
            $chars[] = mb_substr($string, $i, 1);
        }
        return $chars;
    }

    /**
     * Forces a file download
     */
    public static function forceFileDownload($file, $mimetype = 'application/octet-stream') {
        if (file_exists($file) == false || is_readable($file) == false) {
            return false;
        }
        $base = basename($file);
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Disposition: attachment; filename=$base");
        header("Content-Length: " . filesize($file));
        header("Content-Type: $mimetype");
        readfile($file);
        exit();
    }

    /**
     * Forces a file download from content
     */
    public static function forceFileDownloadFromContent($file_name, $file_content, $mimetype = 'application/octet-stream') {
        header("Pragma: public"); // required
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false); // required for certain browsers
        header("Content-Transfer-Encoding: binary");
        header("Content-Type: $mimetype");
        header("Content-Disposition: attachment; filename=\"" . $file_name . "\";");
        header("Content-Length: " . strlen($file_content));
        echo $file_content;
        return true;
    }

    /**
     * Returns IP address of the current user.
     * <code>
     * $ip = \Sinevia\Utils::ip();
     * </code>
     * @return String the IP address
     */
    static function ip() {
        $ip = '';
        if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != "") {
            $ip = $_SERVER['REMOTE_ADDR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] != "") {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != "") {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $ip;
    }

    /**
     * Makes a page text navigation.
     *
     * <code>
     *     $pagination = \Sinevia\Utils::pagination(40,10,2,"http://server.com/page/");
     *     // The generated URLs will be of the type
     *     // http://server.com/page/1 - Previous
     *     // http://server.com/page/3 - Next
     *     $pagination = s_pagination(100,10,2,"http://server.com/page?id=");
     *     // The generated URLs will be of the type
     *     // http://server.com/page?id=1 - Previous
     *     // http://server.com/page?id=3 - Next
     * </code>
     * Result: Prev 1 <b>2</b> 3 4 Next
     * @param int the total number of the items
     * @param int how many items to be shown on the page
     * @param int the number of the current page
     * @param String the URL of the page
     * @return String page navigation
     */
    public static function pagination($num_items, $per_page, $current_page_number, $url, $options = array()) {
        $pagination = "";

        if (is_numeric($num_items) == false) {
            return $pagination;
        }

        if ($num_items < 1) {
            return $pagination;
        }

        $number_of_pages = ceil($num_items / $per_page);
        $url_end = (isset($options['url_end']) == false) ? '' : $options['url_end'];
        $pages_to_show = (isset($options['pages_to_show']) == false) ? 10 : $options['pages_to_show'];

        $pagination .= '<ul class="pagination">';
        if ($current_page_number > 0) {
            $pagination .= "<li><a href='" . $url . ($current_page_number - 1) . $url_end . "'>Prev</a></li>";
        }
        for ($i = 0; $i < $number_of_pages; $i++) {
            if ($i < ($current_page_number - $pages_to_show)) {
                continue;
            }
            if ($i > ($current_page_number + $pages_to_show)) {
                continue;
            }
            if ($i == $current_page_number) {
                $pagination .= "<li><a class=\"active\" href='" . $url . $i . $url_end . "'>" . ($i + 1) . "</a></li>";
            } else {
                $pagination .= "<li><a href='" . $url . $i . $url_end . "'>" . ($i + 1) . "</a></li>";
            }
        }
        if ($current_page_number != ($number_of_pages - 1)) {
            $pagination .= "<li><a href='" . $url . ($current_page_number + 1) . $url_end . "'>Next</a></li>";
        }
        $pagination .= "</ul>";
        return $pagination;
    }

    //========================= START OF METHOD ===========================//
    // METHOD: pagination_reverse                                          //
    //=====================================================================//
    /**
     * Makes a page text navigation.
     *
     * <code>
     *     $pagination = \Sinevia\Utils::paginationReveresed(40,10,2,"http://server.com/page/");
     *     // The generated URLs will be of the type
     *     // http://server.com/page/3 - Previous
     *     // http://server.com/page/1 - Next
     *     $pagination = \Sinevia\Utils::paginationReverse(100,10,2,"http://server.com/page?id=");
     *     // The generated URLs will be of the type
     *     // http://server.com/page?id=3 - Previous
     *     // http://server.com/page?id=1 - Next
     * </code>
     * Result: Prev 1 <b>2</b> 3 4 Next
     * @param int the total number of the items
     * @param int how many items to be shown on the page
     * @param int the number of the current page
     * @param String the URL of the page
     * @return String page navigation
     */
    public static function paginationReversed($num_items, $per_page, $current_page_number, $url, $options = array()) {
        $pagination = "";

        if (is_numeric($num_items) == false) {
            return $pagination;
        }

        if ($num_items < 1) {
            return $pagination;
        }

        $number_of_pages = ceil($num_items / $per_page);
        $url_end = (isset($options['url_end']) == false) ? '' : $options['url_end'];
        $pages_to_show = (isset($options['pages_to_show']) == false) ? 10 : $options['pages_to_show'];

        $pagination .= '<ul class="pagination">';
        // Next
        if ($current_page_number < $number_of_pages) {
            $pagination .= "<li><a href='" . $url . ($current_page_number + 1) . $url_end . "' style='color:blue;'>Next</a></li>";
        }
        for ($i = $number_of_pages; $i > 0; $i--) {

            if ($i < ($current_page_number - $pages_to_show)) {
                continue;
            }
            if ($i > ($current_page_number + $pages_to_show)) {
                continue;
            }

            if ($i == $current_page_number) {
                $pagination .= "<li><a class=\"active\" href='" . $url . $i . $url_end . "'>" . ($i) . "</a></li>";
            } else {
                $pagination .= "<li><a href='" . $url . $i . $url_end . "' style='color:blue;'>" . ($i) . "</a></li>";
            }
        }
        // Prev
        if ($current_page_number > 1) {
            $pagination .= "<li><a href='" . $url . ($current_page_number - 1) . $url_end . "' style='color:blue;'>Prev</a></li>";
        }

        $pagination .= '</ul>';
        return $pagination;
    }

    /**
     * Return an absolute url from a full PHP script path (i.e. __FILE__).
     * Must be a script on the server.
     * @param String the absolute path of the file/folder
     * @param String the absolute path to the root directory
     * @param String the URL to the root directory
     * @return String an absolute URL
     */
    public static function pathToUrl($path, $root_dir, $root_url) {
        // Cleaning the path
        $path = str_replace("/", DIRECTORY_SEPARATOR, $path);
        $path = str_replace("\\", DIRECTORY_SEPARATOR, $path);

        // Removing the root directory from the path
        $relative_path = str_replace($root_dir, "", $path);

        if ($relative_path == "") { // This is the root
            $this_path = array_map("strtolower", explode('/', $_SERVER['SCRIPT_NAME']));
            $a_path = array_map("strtolower", explode(DIRECTORY_SEPARATOR, $path));  // Resolving a bug in PHP that caused WARNING
            $a_path = array_pop($a_path); // Resolving a bug in PHP that caused WARNING
            $script_position = array_search($a_path, $this_path);
            $script_path = implode('/', array_splice($this_path, 0, $script_position + 1));
            if (isset($_SERVER['HTTPS'])) {
                $scheme = 'https';
            } else {
                $scheme = 'http';
            }
            $url = $scheme . '://' . $_SERVER['SERVER_NAME'] . $script_path;
        } else {
            $rurl = $root_url;

            // START: Adding trailing directory separator
            if (self::stringStartsWith($relative_path, DIRECTORY_SEPARATOR) == false) {
                if (self::stringEndsWith($rurl, '/') == false) {
                    $relative_path = DIRECTORY_SEPARATOR . $relative_path;
                }
            }
            // END: Adding trailing directory separator

            if (DIRECTORY_SEPARATOR == "\\") {
                $relative_path = str_replace(DIRECTORY_SEPARATOR, "/", $relative_path);
            }
            $url = $root_url . $relative_path;
        }
        return $url;
    }

    /**
     * Redirects the browser to another page and posts the data specified
     * as associative array
     * @param string $url
     * @param array $data
     */
    public static function redirectAndPostData($url, $data = array()) {
        $form = '<!DOCTYPE HTML>';
        $form .= '<html>';
        $form .= '<body>';
        $form .= '<form name="PHP_POST_FORM" method="post" action="' . $url . '">';
        foreach ($data as $name => $value) {
            $form .= '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
        }
        $form .= '</form>';
        $form .= '<script>PHP_POST_FORM.submit();</script>';
        $form .= '</body>';
        $form .= '</html>';
        echo $form;
        exit;
    }

    /**
     * Redirects the browser to another page
     * @return void
     */
    public static function redirect($url) {
        if (headers_sent() == true) {
            echo "<meta http-equiv=\"refresh\" content=\"0;url=$url\">\r\n";
            exit;
        } else {
            header("Location:" . $url);
            exit;
        }
    }

    /**
     * Raises the time for execution and the memory limit of the application
     * @param int $time
     * @param int $memory
     */
    public static function raiseMemoryLimits($time = 600, $memory = 256) {
        @ini_set('max_execution_time', $time);
        @ini_set('memory_limit', $memory . 'm');

        if (ini_get('max_execution_time') < $time) {
            @ini_set('max_execution_time', $time);
        }

        // Set the memory_limit to a specified minimum (in MB)
        $memory_minimum = $memory;
        $memory_limit = ini_get('memory_limit');

        if ($memory_limit != -1) {// -1 means no limit
            if (strtolower(substr($memory_limit, -1)) == 'g') { // Gigabytes
                // Minimum is 1GB, so no need to raise it
            } elseif (strtolower(substr($memory_limit, -1)) == 'm') {// Megabytes
                if (substr($memory_limit, 0, -1) < $memory_minimum) {
                    @ini_set('memory_limit', $memory_minimum . 'm');
                }
            } elseif (strtolower(substr($memory_limit, -1)) == 'k') {// Kilobytes
                if (substr($memory_limit, 0, -1) < ($memory_minimum * 1024)) {
                    @ini_set('memory_limit', $memory_minimum . 'm');
                }
            } else {
                if (ctype_digit($memory_limit) && $memory_limit < ($memory_minimum * 1024 * 1024)) {
                    @ini_set('memory_limit', $memory_minimum . 'm');
                }
            }
        }
    }

    public static function stringBetween($string, $match_left, $match_right, $ignore_case = false) {
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

    public static function stringCamelize($string, $separator = " ", $remove_separator = false) {
        $string = str_replace($separator, " ", $string);
        $string = ucwords($string);
        if ($remove_separator) {
            $string = str_replace(" ", "", $string);
        } else {
            $string = str_replace(" ", $separator, $string);
        }
        return $string;
    }

    /**
     * Checks whether a string contains only characters specified in the gama.
     * @param String $string
     * @param String $gama
     * @return boolean
     */
    public static function stringContainsOnly($string, $gama) {
        $chars = self::mb_str_to_array($string);
        $gama = self::mb_str_to_array($gama);
        foreach ($chars as $char) {
            if (in_array($char, $gama) == false)
                return false;
        }
        return true;
    }

    /**
     * Checks if a string ends with another string
     * $result = s_str_starts_with("http://server.com",".com");
     * // $result is true
     * </code>
     * @return bool true on success, false otherwise
     */
    public static function stringEndsWith($string, $match) {
        return (substr($string, (strlen($string) - strlen($match)), strlen($match)) == $match) ? true : false;
    }

    /**
     * Checks if a string is email
     * @param type $email
     * @return boolean
     */
    public static function stringIsEmail($email) {
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

    public static function stringLeftFrom($string, $match) {
        $pos = strpos($string, $match);
        if ($pos === false)
            return false;
        return substr($string, 0, $pos);
    }

    /**
     * Returns the first $num words of $string
     */
    public static function stringMaxWords($string, $num, $suffix = '') {
        $words = explode(' ', $string);
        if (count($words) < $num) {
            return $string;
        } else {
            return implode(' ', array_slice($words, 0, $num)) . $suffix;
        }
    }

    /**
     * Returns the first $num letters of $string
     */
    public static function stringMaxLetters($string, $num, $suffix = '') {
        if (strlen($string) < $num) {
            return $string;
        } else {
            return substr($string, 0, $num) . $suffix;
        }
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
     * @return string
     */
    public static function stringRandom($length = 8, $string = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890") {
        $chars = str_split($string);
        shuffle($chars);
        shuffle($chars);
        return implode("", array_splice($chars, 0, $length));
    }

    public static function stringRightFrom($string, $match) {
        $pos = strpos($string, $match);
        if ($pos === false) {
            return false;
        }
        return substr($string, $pos + strlen($match));
    }

    /**
     *  Given a string such as "comment_123" or "id_57", it returns the
     *  final, numeric id.
     *  @return int
     */
    public static function stringSplitId($string) {
        return preg_match('/[_-]([0-9]+)$/', $string, $matches[1]);
    }

    /**
     * Creates a friendly URL slug from a string
     */
    public static function stringSlugify($string) {
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
     * $result = \Sinevia\Utils::stringStartsWith("http://server.com","http://");
     * // $result is true
     * </code>
     * @return bool true on success, false otherwise
     */
    public static function stringStartsWith($string, $match) {
        return (substr($string, 0, strlen($match)) == $match) ? true : false;
    }

    /**
     * Returns the first $num words of $string
     */
    public static function stringToWords($string) {
//        $t= array(' ', "\t", '=', '+', '-', '*', '/', '\\', ',', '.', ';', ':', '[', ']', '{', '}', '(', ')', '<', '>', '&', '%', '$', '@', '#', '^', '!', '?', '~'); // separators
//        $string= str_replace($t, " ", $string);
        $words = explode(' ', $string);
        return $words;
    }

    /**
     * Generates a UUID
     * @return string
     */
    function uuid() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                // 32 bits for "time_low"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                // 16 bits for "time_mid"
                mt_rand(0, 0xffff),
                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 4
                mt_rand(0, 0x0fff) | 0x4000,
                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                mt_rand(0, 0x3fff) | 0x8000,
                // 48 bits for "node"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * XOR Encodes a String
     * Encodes a String with another key String using the
     * XOR encryption.
     * @param String the String to encode
     * @param String the key String
     * @return String the XOR encoded String
     */
    public static function xorEncode($string, $key) {
        for ($i = 0, $j = 0; $i < strlen($string); $i++, $j++) {
            if ($j == strlen($key)) {
                $j = 0;
            }
            $string[$i] = $string[$i] ^ $key[$j];
        }
        return base64_encode($string);
    }

    /**
     * XOR Decodes a String
     *
     * Decodes a XOR encrypted String using the same key String.
     * @param String the String to decode
     * @param String the key String
     * @return String the decoded String
     */
    public static function xorDecode($encstring, $key) {
        $string = base64_decode($encstring);
        for ($i = 0, $j = 0; $i < strlen($string); $i++, $j++) {
            if ($j == strlen($key)) {
                $j = 0;
            }
            $string[$i] = $key[$j] ^ $string[$i];
        }
        return $string;
    }

}
