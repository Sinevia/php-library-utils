<?php

class StringUtilsTest extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testBetween(){
        $initialString = "abcdefghijklmnopqrstuvwxyz";
        $result = \Sinevia\StringUtils::between($initialString,"klm","qrs");
        $this->assertEquals("nop", $result);

        $result = \Sinevia\StringUtils::between($initialString,"KZM","qrs");
        $this->assertNull($result);
    }

    public function testEndsWith(){
        $initialString = "abcdefghijklmnopqrstuvwxyz";
        $result = \Sinevia\StringUtils::endsWith($initialString,"xyz");
        $this->assertTrue($result);

        $result = \Sinevia\StringUtils::endsWith($initialString,"abc");
        $this->assertFalse($result);
    }

    public function testHasOnly() {
        $initialString = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $result = \Sinevia\StringUtils::hasOnly($initialString,'ABC');
        $this->assertFalse($result);

        $initialString = "ABCDEFGHIJkKLMNOPQRSTUVWXYZ";
        $result = \Sinevia\StringUtils::hasOnly($initialString, $initialString);
        $this->assertTrue($result);
    }

    public function testHasLowercase(){
        $initialString = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $result = \Sinevia\StringUtils::hasLowercase($initialString);
        $this->assertFalse($result);

        $initialString = "ABCDEFGHIJkKLMNOPQRSTUVWXYZ";
        $result = \Sinevia\StringUtils::hasLowercase($initialString);
        $this->assertTrue($result);
    }

    public function testHasUppercase(){
        $initialString = "abcdefghijklmnopqrstuvwxyz";
        $result = \Sinevia\StringUtils::hasUppercase($initialString);
        $this->assertFalse($result);

        $initialString = "abcdefghijKklmnopqrstuvwxyz";
        $result = \Sinevia\StringUtils::hasUppercase($initialString);
        $this->assertTrue($result);
    }

    public function testLeftFrom(){
        $initialString = "abcdefghijklmnopqrstuvwxyz";
        $result = \Sinevia\StringUtils::leftFrom($initialString,"def");
        $this->assertEquals("abc", $result);

        $result = \Sinevia\StringUtils::leftFrom($initialString,"KZM");
        $this->assertNull($result);
    }

    public function testRightFrom(){
        $initialString = "abcdefghijklmnopqrstuvwxyz";
        $result = \Sinevia\StringUtils::rightFrom($initialString,"stu");
        $this->assertEquals("vwxyz", $result);

        $result = \Sinevia\StringUtils::rightFrom($initialString,"KZM");
        $this->assertNull($result);
    }

    public function testSlugify(){
        $initialString = "abc Def_ghi";
        $result = \Sinevia\StringUtils::slugify($initialString);
        $this->assertEquals('abc-defghi', $result);
    }

    public function testSnakify(){
        $initialString = "abc Def_ghi";
        $result = \Sinevia\StringUtils::snakify($initialString, "_");
        $this->assertEquals('abc_def_ghi', $result);
    }

    public function testStartsWith(){
        $initialString = "abcdefghijklmnopqrstuvwxyz";
        $result = \Sinevia\StringUtils::startsWith($initialString,"abc");
        $this->assertTrue($result);

        $result = \Sinevia\StringUtils::startsWith($initialString,"xyz");
        $this->assertFalse($result);
    }

    public function testToArray(){
        $initialString = "abc";
        $result = \Sinevia\StringUtils::toArray($initialString);
        $this->assertIsArray($result);
        $this->assertEquals(3, count($result));
    }
}