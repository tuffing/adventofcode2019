<?php 
use PHPUnit\Framework\TestCase;

final class Day10Test extends TestCase
{
    /*public function testPart1Test1(): void
    {
    	$day10 = new Day10("test1-8.txt");

        $this->assertEquals(
            8, $day10->runPart1()
        );
    }*/

    public function testPart1Test2(): void
    {
        $day10 = new Day10("test33.txt");

        $this->assertEquals(
            33, $day10->runPart1()
        );
    }

  /* public function testPart1Test3(): void
    {
        $day10 = new Day10("test35.txt");

        $this->assertEquals(
            35, $day10->runPart1()
        );
    }

    public function testPart1Test4(): void
    {
        $day10 = new Day10("test41.txt");

        $this->assertEquals(
            41, $day10->runPart1()
        );
    }

    public function testPart1Test5(): void
    {
    	$day10 = new Day10("test210.txt");

        $this->assertEquals(
            210, $day10->runPart1()
        );
    }*/
}
