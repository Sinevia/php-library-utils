<?php

class UtilsTest extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testIp(){
        //$_SERVER = [];
        $_SERVER['REMOTE_ADDR'] = '1.0.0.1';
        $ip = \Sinevia\Utils::ip();
        $this->assertEquals($ip, '1.0.0.1');
        
        $_SERVER['REMOTE_ADDR'] = '';
        $_SERVER['HTTP_CLIENT_IP'] = '2.0.0.2';
        $ip = \Sinevia\Utils::ip();
        $this->assertEquals($ip, '2.0.0.2');

        $_SERVER['REMOTE_ADDR'] = '';
        $_SERVER['HTTP_CLIENT_IP'] = '';
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '3.0.0.3';
        $ip = \Sinevia\Utils::ip();
        $this->assertEquals($ip, '3.0.0.3');
    }
}