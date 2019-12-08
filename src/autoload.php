<?php
// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart
// this is an autogenerated file - do not edit
spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
                'day01' => '/Day01.php',
                'day02' => '/Day02.php',
                'day03' => '/Day03.php',
                'day04' => '/Day04.php',
                'day05' => '/Day05.php',
                'day06' => '/Day06.php',
                'day07' => '/Day07.php',
                'iday' => '/iDay.php',
                'inputloader' => '/InputLoader.php',
                'intcodeprocessor' => '/IntCodeVM.php',
                'intcodevm' => '/IntCodeVM.php',
                'permutationiterator' => '/PermutationIterator.php'
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . $classes[$cn];
        }
    },
    true,
    false
);
// @codeCoverageIgnoreEnd
