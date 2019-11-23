<?php 

/**
Static functions to perform common advent of code input loading

Files are always presummed to be in the input folder
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
    	//var_dump($text);
    	preg_match_all('/-?\d+/', $text, $matches);

    	var_dump($matches[0]);
    	return $matches[0];
    }

}