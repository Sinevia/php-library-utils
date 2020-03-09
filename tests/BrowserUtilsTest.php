<?php

class BrowserUtilsTest extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testFingerprint(){
        $fingerprint = \Sinevia\BrowserUtils::fingerprint();
        $this->assertEquals($fingerprint, '_____');

        $_SERVER['HTTP_X_FORWARDED_FOR'] = 'xff';
        $_SERVER['HTTP_USER_AGENT'] = 'ua';
        $_SERVER['HTTP_ACCEPT'] = 'a';
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'al';
        $_SERVER['HTTP_ACCEPT_ENCODING'] = 'ae';
        $_SERVER['HTTP_ACCEPT_CHARSET'] = 'ac';
        $fingerprint = \Sinevia\BrowserUtils::fingerprint();
        $this->assertEquals($fingerprint, 'xff_ua_a_al_ae_ac');
    }
}