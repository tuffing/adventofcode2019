<?php 
use PHPUnit\Framework\TestCase;

final class Day12Test extends TestCase
{
    public function testPart1Test1(): void
    {
    	$day12 = new Day12("test179.txt");

        $this->assertEquals(
            179, $day12->runVels(10)
        );
    }

    public function testPart1Test2(): void
    {
        $day12 = new Day12("test1940.txt");

        $this->assertEquals(
            1940, $day12->runVels(100)
        );
    }

   /* public function testPart2Test1(): void
    {
    	$day12 = new Day12("test1.txt");

        $this->assertEquals(
            2, $day12->runPart2()
        );
    }*/
}
