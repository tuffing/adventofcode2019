<?php 
use PHPUnit\Framework\TestCase;

final class Day19Test extends TestCase
{
    public function testPart1Test1(): void
    {
    	$day19 = new Day19("test1.txt");

        $this->assertEquals(
            1, $day19->runPart1()
        );
    }

    public function testPart2Test1(): void
    {
    	$day19 = new Day19("test1.txt");

        $this->assertEquals(
            2, $day19->runPart2()
        );
    }
}
