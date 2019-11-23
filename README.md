# adventofcode2019

2019 advent of code.

This year I'm going to use PHP 7.3 with PHP Unit for testing.
Most of my php is in the older Drupal hook style, so this year I want to use newer supported class features and data structures.

## running tests

 1. If there's a new class, run `./tools/phpab -o src/autoload.php -b src src`
 2. run the class being tested via php unit `./phpunit --bootstrap ./src/autoload.php ./tests`

## Run the solution

Simply run the following cfrom the command line:

`php main.php [day number]`

e.g 

`php main.php 1` 

The leading 0 is optional, 1 or 01 will work for example (but not 001) .

## Todo

 1. Folder structures
 2. Set up basic app structure with tests and mechinism to run each day individually / together
 3. Set up common functions. Like multi and single line file loading. pull in all numbers etc


## Days

## day 1

## day 2

## day 3

## day 4

## day 5

## day 6

## day 7

## day 8
