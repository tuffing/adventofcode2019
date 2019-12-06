<?php 
use PHPUnit\Framework\TestCase;

final class Day06Test extends TestCase
{
    public function testPart1Test1(): void
    {
    	$day06 = new Day06("test1.txt");

        $this->assertEquals(
            42, $day06->runPart1()
        );
    }

    public function testPart2Test1(): void
    {
    	$day06 = new Day06("test2.txt");
        $day06->runPart1();
        $this->assertEquals(
            4, $day06->runPart2()
        );
    }
}
