<?php 
use PHPUnit\Framework\TestCase;

final class Day24Test extends TestCase
{
    /*public function testPart1Test1(): void
    {
    	$day24 = new Day24("test2129920.txt");

        $this->assertEquals(
            2129920, $day24->runPart1()
        );
    }*/

    public function testPart2Test1(): void
    {
    	$day24 = new Day24("test2129920.txt");

        $this->assertEquals(
            99, $day24->runPart2(10)
        );
    }
}
