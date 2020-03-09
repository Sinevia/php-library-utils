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
 */
class BrowserUtils {
    /**
     * Returns a browser fingerprint of the user based on:
     * - IP
     * - user agent
     * - language
     * - accepted encoding
     * - character set 
     * @return string
     */
    public static function fingerprint() {
        $t1 = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
        $t2 = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $t3 = $_SERVER['HTTP_ACCEPT'] ?? '';
        $t4 = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
        $t5 = $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '';
        $t6 = $_SERVER['HTTP_ACCEPT_CHARSET'] ?? '';

        $token = $t1;
        $token .= '_';
        $token .= $t2;
        $token .= '_';
        $token .= $t3;
        $token .= '_';
        $token .= $t4;
        $token .= '_';
        $token .= $t5;
        $token .= '_';
        $token .= $t6;

        return $token;
    }
}
