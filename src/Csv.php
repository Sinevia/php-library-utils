<?php

namespace Sinevia;

class Csv {

    public static function write($file, $array) {
        $csvTotal = [];
        foreach ($array as $row) {
            $csv = [];
            foreach ($row as $cell) {
                $csv[] = $cell;
            }
            $csvTotal[] = implode(',', $csv);
        }
        file_put_contents($file, implode("\n", $csvTotal));
    }

    public static function read($file) {
        $csvTotal = explode("\n", file_get_contents($file));
        $csv = [];
        foreach ($csvTotal as $row) {
            $row = explode(',', $row);
            $final = [];
            foreach($row as $cell){
                $final[] = base64_decode($cell);
            }
            $csv[] = $final;
        }
        
        return $csv;
    }

}
