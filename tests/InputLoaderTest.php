<?php 
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class InputLoaderTest extends TestCase
{
    public function testCanLoadSingleText(): void
    {


        $this->assertEquals(
            "test text",
            InputLoader::LoadAsTextBlob("input.txt")
        );
    }

    /*public function testCannotBeCreatedFromInvalidEmailAddress(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Email::fromString('invalid');
    }

    public function testCanBeUsedAsString(): void
    {
        $this->assertEquals(
            'user@example.com',
            Email::fromString('user@example.com')
        );
    }*/
}
