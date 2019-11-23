<?php 

/**
* Static functions to perform common advent of code input loading
*
* Files are always presummed to be in the input folder
*/
final class InputLoader
{

	public static function LoadAsTextBlob($path) {
		return file_get_contents("input/$path");
	}

	public static function LoadAsArrayOfLines($path) {
		$lines = file("input/$path");

		//the file function includes line carriages in the array
		foreach ($lines as $key => $value) {
			$lines[$key] = trim($value);
		}

		return $lines;
	}

	public static function FindAndLoadAllNumbersIntoArray($path) {
		$text = InputLoader::LoadAsTextBlob($path);

		preg_match_all('/-?\d+/', $text, $matches);

		return $matches[0];
	}


	/**
	* Allows loading in lines of files parsed directly into stdObjects
	*
	* Also Allows adding extra 'items' to the object that won't be parsed in
	* as to allow meta data etc on the object. common for AoC task
	*
	* @param string $path file path
	* @param string $mapping An array in the following format in which the key is the index of word we are parsing. Following types are accepted, boolean, int, string and float (or any primiate for that matter)
	*	e.g [
	*		0 => ['name' => Field1', 'type'=>'int'],
	*		2 => ['name' => Field2', 'type'=>'string'],
	*		4 => ['name' => Field3', 'type'=>'float'],
	* ] 
	* @param string $extras An array of field names and their defaults. e.g ['extra1' => 'world', 'extra2' => -10]
	*
	* @return array of stdClasses e.g
	*	[{
	*		"field1" -> 4,
	*		"field2" -> "hello",
	*		"field3" -> 1.2,
	*		"extra1" -> "world",
	*		"extra2" -> -10,
	*	},]
	**/
	public static function LoadAsArrayOfObjects($path, $mapping = [], $extras = []) {
		$lines = InputLoader::LoadAsArrayOfLines($path);
		$objects = [];

		foreach ($lines as $value) {
			$obj = (object) $extras;
			$parts = explode(' ', $value);

			foreach ($mapping as $key => $field) {
				settype($parts[$key], $field['type']);
				$obj->{$field['name']} = $parts[$key];
			}

			$objects[] = $obj;
		}

		return $objects;
	}
}