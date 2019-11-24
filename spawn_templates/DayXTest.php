<?php 
use PHPUnit\Framework\TestCase;

final class DayXTest extends TestCase
{
    public function testPart1Test1(): void
    {
    	$dayX = new DayX("test1.txt");

        $this->assertEquals(
            1, $dayX->runPart1()
        );
    }

    public function testPart2Test1(): void
    {
    	$dayX = new DayX("test1.txt");

        $this->assertEquals(
            2, $dayX->runPart2()
        );
    }
}
