<?php 
use PHPUnit\Framework\TestCase;

final class Day14Test extends TestCase
{
    public function testPart1Test1(): void
    {
    	$day14 = new Day14("test1.txt");

        $this->assertEquals(
            31, $day14->runPart1()
        );
    }


    public function testPart1Test165(): void
    {
        $day14 = new Day14("test165.txt");

        $this->assertEquals(
            165, $day14->runPart1()
        );
    }


    public function testPart1Test13312(): void
    {
        $day14 = new Day14("test13312.txt");

        $this->assertEquals(
            13312, $day14->runPart1()
        );
    }

    public function testPart1Test180697(): void
    {
        $day14 = new Day14("test180697.txt");

        $this->assertEquals(
            180697, $day14->runPart1()
        );
    }
  
  /* public function testPart2Test13312(): void
    {
    	$day14 = new Day14("test13312.txt");

        $this->assertEquals(
            82892753, $day14->runPart2()
        );
    }*/
}
