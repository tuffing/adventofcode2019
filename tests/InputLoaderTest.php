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

	public function testParseIntoObjects(): void
	{	
		$mapping = [
			0 => ['name' => 'count', 'type' => 'int'],
			1 => ['name' => 'animal', 'type' => 'string'],
			3 => ['name' => 'truth', 'type' => 'boolean'],
			5 => ['name' => 'realness', 'type' => 'float'],
		];
		$extras = ['remaining' => 2];
		$arr = InputLoader::LoadAsArrayOfObjects('inputloader/objectTest.txt', $mapping, $extras);

		//extra -- remaining
		$this->assertEquals($arr[1]->remaining,2);

		//animal - string
		$this->assertEquals($arr[0]->animal,'pig');
		$this->assertEquals($arr[1]->animal,'dogs');
		$this->assertEquals($arr[2]->animal,'owls');
		
		//count - int
		$this->assertEquals($arr[0]->count,1);
		$this->assertIsInt($arr[1]->count);
		$this->assertEquals($arr[1]->count,2);
		$this->assertIsInt($arr[2]->count);
		$this->assertEquals($arr[2]->count,6);

		//truth -- boolean
		$this->assertEquals($arr[0]->truth,true);
		$this->assertIsBool($arr[1]->truth);
		$this->assertEquals($arr[1]->truth,false);

		//realness - float
		$this->assertEquals($arr[0]->realness,-1.2);
		$this->assertIsFloat($arr[1]->realness);
		$this->assertEquals($arr[1]->realness,2.2);
		$this->assertIsFloat($arr[2]->realness);
		$this->assertEquals($arr[2]->realness,55);
	}
}
