<?php 
if (!getenv('AOC_SESSION')) {
	print('WARNING: no advent of code session set'. PHP_EOL);
}

if (isset($argv[0]) && $argv[0] == 'fetchinput.php') {
	if (!isset($argv[1])) {
		print('Run as follows:'. PHP_EOL);
		print('php fetchinput.php [Day number]'. PHP_EOL);
		print('php fetchinput.php 2'. PHP_EOL);
		print('This will override the input.txt file for day 2 with a fresh copy from the site.'. PHP_EOL);
		print('Environment variable AOC_SESSION must be set with your session varible for the site.'. PHP_EOL);
		print('If run before the input is available, output will just 404'. PHP_EOL);

		return;
	}
	fetch_input($argv[1]);
}

function fetch_input($day) {
	$year = 2019;
	$path = "https://adventofcode.com/$year/day/$day/input";
	$session = getenv('AOC_SESSION');
	
	$context = stream_context_create(array(
	'http' => array(
		'method' => 'GET',
		'header' => "Cookie: session=$session\r\n"
	)
	));
	$data = file_get_contents($path, false, $context);

	if ($data === false) {
		print('INPUT NOT FETCHED - Input not ready yet OR AOC_SESSION environment variable is incorrectly set!'. PHP_EOL);
		print('AOC_SESSION is set to: '. $session. PHP_EOL);
		return;
	} 

	//override the existing input file:
	$day_name = 'Day'. str_pad($day, 2, '0', STR_PAD_LEFT);

	file_put_contents("input/$day_name/input.txt", trim($data));

	print("Input fetched". PHP_EOL);
}