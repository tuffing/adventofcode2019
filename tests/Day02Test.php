<?php 
use PHPUnit\Framework\TestCase;

final class Day02Test extends TestCase
{
    public function testPart1Test1(): void
    {
    	$day02 = new Day02("test1.txt");

        $this->assertEquals(
            1, $day02->runPart1()
        );
    }

    public function testPart2Test1(): void
    {
    	$day02 = new Day02("test1.txt");

        $this->assertEquals(
            2, $day02->runPart2()
        );
    }
}
