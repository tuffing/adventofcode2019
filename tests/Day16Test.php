<?php 
use PHPUnit\Framework\TestCase;

final class Day16Test extends TestCase
{
    public function testPart1Test1(): void
    {
    	$day16 = new Day16("test1.txt");

        $this->assertEquals(
            1, $day16->runPart1()
        );
    }

    public function testPart2Test1(): void
    {
    	$day16 = new Day16("test1.txt");

        $this->assertEquals(
            2, $day16->runPart2()
        );
    }
}
