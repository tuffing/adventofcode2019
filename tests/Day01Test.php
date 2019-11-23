<?php 
use PHPUnit\Framework\TestCase;

final class Day01Test extends TestCase
{
    public function testPart1Test1(): void
    {
    	$day1 = new Day01("Day01/test1.txt");

        $this->assertEquals(
            1, $day1->runPart1()
        );
    }


    public function testPart2Test1(): void
    {
    	$day1 = new Day01("Day01/test1.txt");

        $this->assertEquals(
            2, $day1->runPart2()
        );
    }
}
