<?php 
use PHPUnit\Framework\TestCase;

final class Day03Test extends TestCase
{
    public function testPart1Test1(): void
    {
    	$day03 = new Day03("test1.txt");

        $this->assertEquals(
            159, $day03->runPart1()
        );

        $this->assertEquals(
            610, $day03->runPart2()
        );
    }

   /* public function testPart2Test1(): void
    {
    	$day03 = new Day03("test1.txt");

        $this->assertEquals(
            2, $day03->runPart2()
        );
    }*/
}
