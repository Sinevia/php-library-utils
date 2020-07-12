<?php

class ArrayUtilsTest extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }


    public function testIsAssoc(){
        $array1 = [];
        $result = \Sinevia\ArrayUtils::isAssoc($array1);
        $this->assertFalse($result);

        $array2 = [1, 2, 3];
        $result = \Sinevia\ArrayUtils::isAssoc($array2);
        $this->assertFalse($result);

        $array1 = ["name" => "Jane"];
        $result = \Sinevia\ArrayUtils::isAssoc($array1);
        $this->assertTrue($result);
    }

    public function testToCsV(){
        $array1 = [];
        $result = \Sinevia\ArrayUtils::toCsv($array1);
        $this->assertEquals("", $result);

        $array2 = [[1, 2, 3]];
        $result = \Sinevia\ArrayUtils::toCsv($array2);
        $this->assertEquals("\"1\",\"2\",\"3\"\n", $result);

        $array2 = [[1, 2, 3]];
        $result = \Sinevia\ArrayUtils::toCsv($array2,false);
        $this->assertEquals("1,2,3\n", $result);


        $array1 = [
            [
                "first_name" => "Jane",
                "last_name" => "Austin"
            ],
            [
                "first_name" => "Charles",
                "last_name" => "Dickens"
            ]
        ];
        $result = \Sinevia\ArrayUtils::ToCsv($array1);
        $this->assertEquals("first_name,last_name\n\"Jane\",\"Austin\"\n\"Charles\",\"Dickens\"\n", $result);
    }
}