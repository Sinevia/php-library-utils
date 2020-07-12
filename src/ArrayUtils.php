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

class ArrayUtils {
    /**
     * Returns the specified columns
     * @return array
     */
    public static function columns(array $arr, array $keysSelect)
    {    
        $keys = array_flip($keysSelect);
        $filteredArray = array_map(function($a) use($keys){
            return array_intersect_key($a,$keys);
        }, $arr);

        return $filteredArray;
    }
    
    
    /**
     * Returns whether the supplied array is associative
     * @return array
     */
    public static function isAssoc(array $array)
    {
        if (array() === $array) {
            return false;
        }
        if (count($array) < 1){
            return false;
        }
        return array_keys($array) !== range(0, count($array) - 1);
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
    public static function keyDelete($array, $key) {
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
    public static function toColumns(array $array, int $columns) {
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
     * @return string
     */
    public static function toCsv(array $array, $quoteAlways = true) {
        $headers = [];

        if (count($array) > 0 AND self::isAssoc($array[0])) {
            $headers = array_keys($array[0]);
        }

        ob_start();
        $fp = fopen('php://output', 'w');

        if (count($headers) > 0) {
            fputcsv($fp, $headers);
        }

        foreach ($array as $row) {
            if ($quoteAlways) {
                fputs($fp, implode(",", array_map(function ($value) {
                                    ///remove any ESCAPED double quotes within string.
                                    $value = str_replace('\\"', '"', $value);
                                    //then force escape these same double quotes And Any UNESCAPED Ones.
                                    $value = str_replace('"', '\"', $value);
                                    //force wrap value in quotes and return
                                    return '"' . $value . '"';
                                }, $row)) . "\n");
            } else {
                fputcsv($fp, $row);
            }
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
    public static function valueDelete(array $array, $value) {
        $value_index = array_keys(array_values($array), $value);
        if (count($value_index) != '') {
            array_splice($array, $value_index[0], 1);
        }
        return $array;
    }
}
