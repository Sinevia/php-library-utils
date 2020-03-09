<?php

class DataUtilsTest extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testSerializeUnserialize(){
        $data = ['Key1'=>'Value1'];
        $serialized = \Sinevia\DataUtils::serialize($data);
        $unserialized = \Sinevia\DataUtils::unserialize($serialized);

        $this->assertIsArray($unserialized);
        $this->assertArrayHasKey('Key1', $unserialized);
        $this->assertEquals($unserialized['Key1'], 'Value1');
    }

    public function testUnserializeWithIncorrectData(){
        $incorrectData = 'Value1';
        $unserialized = \Sinevia\DataUtils::unserialize($incorrectData);

        $this->assertNull($unserialized);
        $this->assertIsNotString($unserialized);
        //$this->assertEquals($unserialized['Key1'], 'Value1');
    }
}