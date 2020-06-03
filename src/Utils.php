<?php

// ========================================================================= //
// SINEVIA PUBLIC                                        http://sinevia.com  //
// ------------------------------------------------------------------------- //
// COPYRIGHT (c) 2008-2020 Sinevia Ltd                   All rights reserved //
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
 * Class with general type convenience functions
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
    
    public static function colorNameToHex($color) {
        $colours = [
            "aliceblue" => "#f0f8ff", "antiquewhite" => "#faebd7", "aqua" => "#00ffff", "aquamarine" => "#7fffd4", "azure" => "#f0ffff",
            "beige" => "#f5f5dc", "bisque" => "#ffe4c4", "black" => "#000000", "blanchedalmond" => "#ffebcd", "blue" => "#0000ff", "blueviolet" => "#8a2be2", "brown" => "#a52a2a", "burlywood" => "#deb887",
            "cadetblue" => "#5f9ea0", "chartreuse" => "#7fff00", "chocolate" => "#d2691e", "coral" => "#ff7f50", "cornflowerblue" => "#6495ed", "cornsilk" => "#fff8dc", "crimson" => "#dc143c", "cyan" => "#00ffff",
            "darkblue" => "#00008b", "darkcyan" => "#008b8b", "darkgoldenrod" => "#b8860b", "darkgray" => "#a9a9a9", "darkgreen" => "#006400", "darkkhaki" => "#bdb76b", "darkmagenta" => "#8b008b", "darkolivegreen" => "#556b2f",
            "darkorange" => "#ff8c00", "darkorchid" => "#9932cc", "darkred" => "#8b0000", "darksalmon" => "#e9967a", "darkseagreen" => "#8fbc8f", "darkslateblue" => "#483d8b", "darkslategray" => "#2f4f4f", "darkturquoise" => "#00ced1",
            "darkviolet" => "#9400d3", "deeppink" => "#ff1493", "deepskyblue" => "#00bfff", "dimgray" => "#696969", "dodgerblue" => "#1e90ff",
            "firebrick" => "#b22222", "floralwhite" => "#fffaf0", "forestgreen" => "#228b22", "fuchsia" => "#ff00ff",
            "gainsboro" => "#dcdcdc", "ghostwhite" => "#f8f8ff", "gold" => "#ffd700", "goldenrod" => "#daa520", "gray" => "#808080", "green" => "#008000", "greenyellow" => "#adff2f",
            "honeydew" => "#f0fff0", "hotpink" => "#ff69b4",
            "indianred " => "#cd5c5c", "indigo" => "#4b0082", "ivory" => "#fffff0", "khaki" => "#f0e68c",
            "lavender" => "#e6e6fa", "lavenderblush" => "#fff0f5", "lawngreen" => "#7cfc00", "lemonchiffon" => "#fffacd", "lightblue" => "#add8e6", "lightcoral" => "#f08080", "lightcyan" => "#e0ffff", "lightgoldenrodyellow" => "#fafad2",
            "lightgrey" => "#d3d3d3", "lightgreen" => "#90ee90", "lightpink" => "#ffb6c1", "lightsalmon" => "#ffa07a", "lightseagreen" => "#20b2aa", "lightskyblue" => "#87cefa", "lightslategray" => "#778899", "lightsteelblue" => "#b0c4de",
            "lightyellow" => "#ffffe0", "lime" => "#00ff00", "limegreen" => "#32cd32", "linen" => "#faf0e6",
            "magenta" => "#ff00ff", "maroon" => "#800000", "mediumaquamarine" => "#66cdaa", "mediumblue" => "#0000cd", "mediumorchid" => "#ba55d3", "mediumpurple" => "#9370d8", "mediumseagreen" => "#3cb371", "mediumslateblue" => "#7b68ee",
            "mediumspringgreen" => "#00fa9a", "mediumturquoise" => "#48d1cc", "mediumvioletred" => "#c71585", "midnightblue" => "#191970", "mintcream" => "#f5fffa", "mistyrose" => "#ffe4e1", "moccasin" => "#ffe4b5",
            "navajowhite" => "#ffdead", "navy" => "#000080",
            "oldlace" => "#fdf5e6", "olive" => "#808000", "olivedrab" => "#6b8e23", "orange" => "#ffa500", "orangered" => "#ff4500", "orchid" => "#da70d6",
            "palegoldenrod" => "#eee8aa", "palegreen" => "#98fb98", "paleturquoise" => "#afeeee", "palevioletred" => "#d87093", "papayawhip" => "#ffefd5", "peachpuff" => "#ffdab9", "peru" => "#cd853f", "pink" => "#ffc0cb", "plum" => "#dda0dd", "powderblue" => "#b0e0e6", "purple" => "#800080",
            "red" => "#ff0000", "rosybrown" => "#bc8f8f", "royalblue" => "#4169e1",
            "saddlebrown" => "#8b4513", "salmon" => "#fa8072", "sandybrown" => "#f4a460", "seagreen" => "#2e8b57", "seashell" => "#fff5ee", "sienna" => "#a0522d", "silver" => "#c0c0c0", "skyblue" => "#87ceeb", "slateblue" => "#6a5acd", "slategray" => "#708090", "snow" => "#fffafa", "springgreen" => "#00ff7f", "steelblue" => "#4682b4",
            "tan" => "#d2b48c", "teal" => "#008080", "thistle" => "#d8bfd8", "tomato" => "#ff6347", "turquoise" => "#40e0d0",
            "violet" => "#ee82ee",
            "wheat" => "#f5deb3", "white" => "#ffffff", "whitesmoke" => "#f5f5f5",
            "yellow" => "#ffff00", "yellowgreen" => "#9acd32"
        ];

        if (isset($colours[strtolower($color)]) == true) {
            return $colours[strtolower($color)];
        }

        return $color;
    }
    
    public static function colorBrightness($color) {
        $hex = self::colorNameToHex($color);
        $hex = trim("#", $hex);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $computed = sqrt(
                ($r * $r * 0.241) +
                ($g * $g * 0.691) +
                ($b * $b * 0.068)
        );

//        if ($computed < 130) {
//            return 'dark';
//        } else {
//            return 'light';
//        }
        
        if ($r + $g + $b > 382) {
            return "light";
        } else {
            return "dark";
        }
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
                $i++;
                
                if ($time < $unit) {
                    $i++;
                    continue;
                }
                
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
     * Checks if script is running on the command-line interface (CLI)
     * @access public
     * @return boolean true, if running on the command-line interface (CLI); false, otherwise
     */
    function isCli() {
        return PHP_SAPI === 'cli' || defined('STDIN');
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
     * Parses command line arguments
     *
     * <code>
     *     $args = \Sinevia\Utils::parseArguments()['arguments'];
     *     $parameters = \Sinevia\Utils::parseArguments()['parameters'];
     * </code>
     *
     * @return array
     */
    public static function parseArguments($argv = null) {
        $argv = $argv ? $argv : $_SERVER['argv'];
        $o = ['arguments' => [], 'parameters' => []];

        array_shift($argv);
        $o = ['arguments' => [], 'parameters' => []];
        for ($i = 0, $j = count($argv); $i < $j; $i++) {
            $a = $argv[$i];
            if (substr($a, 0, 2) == '--') {
                $eq = strpos($a, '=');
                if ($eq !== false) {
                    $o['parameters'][substr($a, 2, $eq - 2)] = substr($a, $eq + 1);
                } else {
                    $k = substr($a, 2);
                    if ($i + 1 < $j && $argv[$i + 1][0] !== '-') {
                        $o['parameters'][$k] = $argv[$i + 1];
                        $i++;
                    } else if (!isset($o[$k])) {
                        $o['parameters'][$k] = true;
                    }
                }
            } else if (substr($a, 0, 1) == '-') {
                if (substr($a, 2, 1) == '=') {
                    $o['parameters'][substr($a, 1, 1)] = substr($a, 3);
                } else {
                    foreach (str_split(substr($a, 1)) as $k) {
                        if (!isset($o[$k])) {
                            $o['parameters'][$k] = true;
                        }
                    }
                    if ($i + 1 < $j && $argv[$i + 1][0] !== '-') {
                        $o['parameters'][$k] = $argv[$i + 1];
                        $i++;
                    }
                }
            } else {
                $o['arguments'][] = $a;
            }
        }
        return $o;
    }

    /**
     * Calculates percentage given current position and total.
     * If postfix provided (i.e. "%") will return the percentage with the postfix appended.
     * @param number $current
     * @param number $total
     * @param number $precission
     * @param number $postfix
     * @return number|string Percentage as number, or as string if postfix provided
     */
    public static function percents($current, $total, $precission = 2, $postfix = null) {
        $percents = ($current / $total) * 100;
        $percentsRounded = round($percents, $precission);
        if ($postfix != null) {
            $percentsRounded = $percentsRounded . $postfix;
        }
        return $percentsRounded;
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
    
    /**
     * Parses an RSS feed and returns an array
     * @param string $url the URL of the RSS feed
     * @return array
     */
    public static function rssToArray($url) {
        $fileContents = file_get_contents($url);
        $simpleXml = (array) simplexml_load_string($fileContents);
        $json = json_encode($simpleXml);
        $array = json_decode($json, TRUE);
        return $array;
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
