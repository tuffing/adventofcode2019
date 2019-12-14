<?php 
use PHPUnit\Framework\TestCase;

final class Day13Test extends TestCase
{
    public function testPart1Test1(): void
    {
    	$day13 = new Day13("test1.txt");

        $this->assertEquals(
            1, $day13->runPart1()
        );
    }

    public function testPart2Test1(): void
    {
    	$day13 = new Day13("test1.txt");

        $this->assertEquals(
            2, $day13->runPart2()
        );
    }
}
