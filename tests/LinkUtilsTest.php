<?php

class LinkUtilsTest extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testRootUrl(){
        $path = "/";
        $link = \Sinevia\LinkUtils::buildUrl($path);
        $this->assertEquals("/", $link);

        $path = "/";
        $link = \Sinevia\LinkUtils::buildUrl($path,['a'=>'&pound;','b'=>'Hi, there!']);
        $this->assertEquals("/?a=%26pound%3B&b=Hi%2C+there%21", $link);

        $path = "/?c=Hello";
        $link = \Sinevia\LinkUtils::buildUrl($path,['a'=>'&pound;','b'=>'Hi, there!']);
        $this->assertEquals("/?c=Hello&a=%26pound%3B&b=Hi%2C+there%21", $link);

        $path = "";
        $link = \Sinevia\LinkUtils::buildUrl($path);
        $this->assertEquals("/", $link);

        $path = "";
        $link = \Sinevia\LinkUtils::buildUrl($path,['a'=>'&pound;','b'=>'Hi, there!']);
        $this->assertEquals("/?a=%26pound%3B&b=Hi%2C+there%21", $link);

        $path = "?c=Hello";
        $link = \Sinevia\LinkUtils::buildUrl($path,['a'=>'&pound;','b'=>'Hi, there!']);
        $this->assertEquals("/?c=Hello&a=%26pound%3B&b=Hi%2C+there%21", $link);
    }

    public function testInternalUrl(){
        $path = "/auth/login";
        $link = \Sinevia\LinkUtils::buildUrl($path);
        $this->assertEquals("/auth/login", $link);

        $path = "/auth/login";
        $link = \Sinevia\LinkUtils::buildUrl($path,['a'=>'&pound;','b'=>'Hi, there!']);
        $this->assertEquals("/auth/login?a=%26pound%3B&b=Hi%2C+there%21", $link);

        $path = "/auth/login?c=Well";
        $link = \Sinevia\LinkUtils::buildUrl($path,['a'=>'&pound;','b'=>'Hi, there!']);
        $this->assertEquals("/auth/login?c=Well&a=%26pound%3B&b=Hi%2C+there%21", $link);
    }

    public function testExternalUrl(){
        $path = "https://sinevia.com";
        $link = \Sinevia\LinkUtils::buildUrl($path);
        $this->assertEquals("https://sinevia.com", $link);

        $path = "https://sinevia.com";
        $link = \Sinevia\LinkUtils::buildUrl($path,['a'=>'&pound;','b'=>'Hi, there!']);
        $this->assertEquals("https://sinevia.com?a=%26pound%3B&b=Hi%2C+there%21", $link);

        $path = "https://sinevia.com?c=Well";
        $link = \Sinevia\LinkUtils::buildUrl($path,['a'=>'&pound;','b'=>'Hi, there!']);
        $this->assertEquals("https://sinevia.com?c=Well&a=%26pound%3B&b=Hi%2C+there%21", $link);

        $path = "https://sinevia.com/";
        $link = \Sinevia\LinkUtils::buildUrl($path);
        $this->assertEquals("https://sinevia.com/", $link);

        $path = "https://sinevia.com/";
        $link = \Sinevia\LinkUtils::buildUrl($path,['a'=>'&pound;','b'=>'Hi, there!']);
        $this->assertEquals("https://sinevia.com/?a=%26pound%3B&b=Hi%2C+there%21", $link);

        $path = "https://sinevia.com/?c=Well";
        $link = \Sinevia\LinkUtils::buildUrl($path,['a'=>'&pound;','b'=>'Hi, there!']);
        $this->assertEquals("https://sinevia.com/?c=Well&a=%26pound%3B&b=Hi%2C+there%21", $link);
    }

    public function testBaseUrl(){
        \Sinevia\LinkUtils::setBaseUrl('localhost:32323');
        $path = "/";
        $link = \Sinevia\LinkUtils::buildUrl($path);
        $this->assertEquals("http://localhost:32323", $link);

        $path = "/auth/login";
        $link = \Sinevia\LinkUtils::buildUrl($path);
        $this->assertEquals("http://localhost:32323/auth/login", $link);

        $path = "/auth/login";
        $link = \Sinevia\LinkUtils::buildUrl($path,['a'=>'&pound;','b'=>'Hi, there!']);
        $this->assertEquals("http://localhost:32323/auth/login?a=%26pound%3B&b=Hi%2C+there%21", $link);

        $path = "/auth/login?c=Well";
        $link = \Sinevia\LinkUtils::buildUrl($path,['a'=>'&pound;','b'=>'Hi, there!']);
        $this->assertEquals("http://localhost:32323/auth/login?c=Well&a=%26pound%3B&b=Hi%2C+there%21", $link);
    }
}