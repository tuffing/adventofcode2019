<?php 
include_once('src/autoload.php');

if (isset($argv[2])) {
	runDay($argv[1], $argv[2]);
}
else if (isset($argv[1])) {
	runDay($argv[1]);
}
else {
	print('Run as follows:'. PHP_EOL);
	print('php main.php [Day number] [optional alt input file or value]'. PHP_EOL);
	print('Alternative inputs must be in that days input folder.'. PHP_EOL);
	print('e.g `php main.php 1`'. PHP_EOL);
	print('e.g `php main.php 2 input123`'. PHP_EOL);
	print('e.g `php main.php 3 alt-input.txt` -- where alt-input.txt is kept in input/Day03/'. PHP_EOL);
}

/**
Just a simple loader mechanism to allow running advent of code solutions from the command line

Will expect there to be a iDay object for this day
**/
function runDay($day, $input = "input.txt") {
	$day_class_name = 'Day'. str_pad($day, 2, '0', STR_PAD_LEFT);
	print($input);
	$day = new $day_class_name($input);
	$part1 = $day->runPart1();
	$part2 = $day->runPart2();

	print PHP_EOL;
	print("$day_class_name --- Part1: $part1 | Part2: $part2");
	print PHP_EOL;
	print PHP_EOL;
}