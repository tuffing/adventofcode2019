<?php 
include_once('src/autoload.php');

if (isset($argv[1])) {
	runDay($argv[1]);
}
else {
	print('Run as follows:'. PHP_EOL);
	print('php main.php [Day number]'. PHP_EOL);
	print('e.g `php main.php 1`'. PHP_EOL);
}

/**
Just a simple loader mechanism to allow running advent of code solutions from the command line

Will expect there to be both a iDay object for this day + an input.txt in the appropriate file for it
**/
function runDay($day) {
	$day_class_name = 'Day'. str_pad($day, 2, '0', STR_PAD_LEFT);
	$day = new $day_class_name("$day_class_name/input.txt");
	$part1 = $day->runPart1();
	$part2 = $day->runPart2();

	print PHP_EOL;
	print("$day_class_name --- Part1: $part1 | Part2: $part2");
	print PHP_EOL;
	print PHP_EOL;
}