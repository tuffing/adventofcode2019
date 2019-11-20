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
   
}