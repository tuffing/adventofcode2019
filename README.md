# adventofcode2019

2019 advent of code.

This year I'm going to use PHP 7.3 with PHP Unit for testing.
Most of my php is in the older Drupal hook style, so this year I want to use newer supported class features and data structures.

@ todo:
 * useful helpers: pretty print grid -> to cli AND to file (append)

## running tests

 1. If there's a new class and it has not been run yet, run `./tools/phpab -o src/autoload.php -b src src`
 2. run the class being tested via php unit `./tools/phpunit --bootstrap ./src/autoload.php ./tests`

## Prepare a new day

There is a script to generate a days stubs and then regenerate the autoload:

`php spawn.php [day number]`

e.g

`php spawn.php 3`

This will:
 1. Create a stub Day03.php file in src
 2. Create a stub Day03Test.php in tests
 3. Run the phpab command to prep the autoloader.
 4. Fetch that days input.

From this moment solutions and tests for Day 3 can be run.

### Force prepare an already existing day:

Use the flag `--force`. obviously this will override any work on that day. Use with care.

## Run the solution

Simply run the following cfrom the command line:

`php main.php [day number] [optional input file or location]` 

The leading 0 for the day is optional, 1 or 01 will work for example (but not 001) .

If you supply an alternatie location, it _must_ be in that days input folder.

e.g 

`php main.php 1` 

or

`php main.php 1 alternative-input.txt` 

or

`php main.php 1 singlevaluewithoutspaces` 

## Fetch a days input

If the input has already unlocked you can autofetch it with:

`php fetchinput.php [day]` 

The spawn command will also attempt to do this.

You will need your Advent of code session cookie set as the AOC_SESSION environment variable. Or the request will 401. 


## Days

## day 1

Grab some numbers, run "floor(x /3) -1 " on them all and sum them.

Part two is to repeat the process but repeat the math on each until you get to 0.

## day 2

## day 3

## day 4

## day 5

## day 6

## day 7

## day 8
