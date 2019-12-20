<?php 
use PHPUnit\Framework\TestCase;

final class Day15Test extends TestCase
{
    public function testPart1Test1(): void
    {
    	$day15 = new Day15("test1.txt");

        $this->assertEquals(
            1, $day15->runPart1()
        );
    }

    public function testPart2Test1(): void
    {
    	$day15 = new Day15("test1.txt");

        $this->assertEquals(
            2, $day15->runPart2()
        );
    }
}
