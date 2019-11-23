<?php 
//declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class InputLoaderTest extends TestCase
{
	public function testCanLoadSingleText(): void
	{
		$this->assertEquals(
			"test text",
			InputLoader::LoadAsTextBlob("inputloader/singleline.txt")
		);
	}

	public function testCanLoadMultiLine(): void
	{
		$arr = InputLoader::LoadAsArrayOfLines("inputloader/multiline.txt");
		$this->assertEquals(4,count($arr));
		$this->assertEquals('abc',$arr[0]);
		$this->assertEquals('jkl',$arr[3]);
	}

	public function testCanLoadNumbers(): void
	{
		$arr = InputLoader::FindAndLoadAllNumbersIntoArray("inputloader/numberlist.txt");
		$this->assertEquals(1,$arr[0]);
		$this->assertEquals(-10,$arr[5]);
	}
}
