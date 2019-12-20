<?php 
use PHPUnit\Framework\TestCase;

final class Day17Test extends TestCase
{
    public function testPart1Test1(): void
    {
    	$day17 = new Day17("test1.txt");

        $this->assertEquals(
            1, $day17->runPart1()
        );
    }

    public function testPart2Test1(): void
    {
    	$day17 = new Day17("test1.txt");

        $this->assertEquals(
            2, $day17->runPart2()
        );
    }
}
