<?php

/** Simple script to spawn a new day if one doesn't exist */

if (!isset($argv[1])) {
	print('Run as follows:'. PHP_EOL);
	print('php spawn.php [Day number]'. PHP_EOL);
	print('php spawn.php 2'. PHP_EOL);

	return;
}

$day = $argv[1];
$day_class_name = 'Day'. str_pad($day, 2, '0', STR_PAD_LEFT);

if (file_exists("src/$day_class_name.php") || file_exists("src/$day_class_nameTest.php")) {
	print('This day already exist'. PHP_EOL);

	return;
}

//day stub
$day_template = file_get_contents("spawn_templates/DayX.php");
$day_template = str_replace("DayX", $day_class_name, $day_template);
file_put_contents("src/$day_class_name.php", $day_template);

//test stub
$test_template = file_get_contents("spawn_templates/DayXTest.php");
$test_template = str_replace("DayX", $day_class_name, $test_template);
$test_template = str_replace("dayX", strtolower($day_class_name), $test_template);
file_put_contents("tests/". $day_class_name ."Test.php", $test_template);

// regenerate autoload
print(shell_exec('./tools/phpab -o src/autoload.php -b src src'). PHP_EOL);