<?php
namespace Sinevia;

class ArrayUtils {
    /**
     * Returns the speified columns
     */
    function columns(array $arr, array $keysSelect)
    {    
        $keys = array_flip($keysSelect);
        $filteredArray = array_map(function($a) use($keys){
            return array_intersect_key($a,$keys);
        }, $arr);

        return $filteredArray;
    }
}
