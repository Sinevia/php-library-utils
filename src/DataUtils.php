<?php

// ========================================================================= //
// SINEVIA CONFIDENTIAL                                  http://sinevia.com  //
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

class DataUtils {
    /**
     * Serializes given data, using a password.
     * Useful for sending unmodifiable tokens in links
     * @param mix $data
     * @return String
     */
    public static function serialize($data, $password='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') {
        $serialized = json_encode($data);
        $encoded = self::xorEncode($serialized, $password);
        $base64ed = base64_encode($encoded);
        return $base64ed;
    }

    /**
     * Unserializes data, using a password.
     * Useful when receiving tokens from links
     * @param String $data
     * @return mix
     */
    public static function unserialize($base64ed, $password='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') {
        $encoded = base64_decode($base64ed);
        if ($encoded === false) {
            return false;
        }
        $decoded = self::xorDecode($encoded, $password);
        $data = json_decode($decoded,true);
        return $data;
    }
    
    /**
     * XOR Encodes a String
     * Encodes a String with another key String using the
     * XOR encryption.
     * @param String the String to encode
     * @param String the key String
     * @return String the XOR encoded String
     */
    private static function xorEncode($string, $key) {
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
    private static function xorDecode($encstring, $key) {
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
