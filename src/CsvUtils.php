<?php

namespace Sinevia;

class CsvUtils {

    public static function write(string $file, array $array) {
        $csv = ArrayUtils::toCsv($array);
        file_put_contents($file, $csv);
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
