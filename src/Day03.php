<?php 
class Day03 implements iDay
{
	private $_input = [];
	private $_part2 = 1;

	public function __construct($input) {
		$this->setInput($input);
	}

	public function runPart1() {
		$coords = [];
		$min_p1 = 1000;
		$min_p2 = 10000;

		$x = 0;
		$y = 0;
		$steps = 0;

		//line/circuit 1. we need to track coords and steps for this one
		foreach ($this->_input[0] as $value) {
			$mod_x = 0;
			$mod_y = 0;
			$this->getDir($value, $mod_x, $mod_y);

			$length = substr($value , 1);

			for ($i = 0; $i < $length; $i++) {
				$steps++;
				$x += $mod_x;
				$y += $mod_y;
				$coords["$x,$y"] = $steps;
			}

		}

		//circuit 2. no need to record coords or steps. if the array has said key it's an intersection
		$x = 0;
		$y = 0;
		$steps = 0;
		foreach ($this->_input[1] as $value) {		
			$mod_x = 0;
			$mod_y = 0;

			$this->getDir($value, $mod_x, $mod_y);

			$length = substr($value , 1);

			for ($i = 0; $i < $length; $i++) {
				$x += $mod_x;
				$y += $mod_y;
				$steps++;

				if (array_key_exists("$x,$y", $coords)) {
					$dist = abs($x) + abs($y);
					$combined = $steps + $coords["$x,$y"];

					if ($min_p1 > $dist) {
						$min_p1 = $dist;
					}

					if ($min_p2 > $combined) {
						$min_p2 = $combined;
					}
				} 
			}

		}

		$this->_part2 = $min_p2;
		return $min_p1;
	}

	public function runPart2() {
		return $this->_part2;
	}

	private function getDir($value, &$mod_x, &$mod_y) {
		$start = substr($value , 0, 1);
	
		if ($start == 'U') {
			$mod_y = -1;
		}
		else if ($start == 'D') {
			$mod_y = 1;
		}
		else if ($start == 'L') {
			$mod_x = -1;
		}
		else {
			$mod_x = 1;
		}
	}

	public function setInput($input) {
		//parse input
		$file = "Day03/".$input;
		$lines = InputLoader::LoadAsArrayOfLines($file);

		$this->_input[] = explode(',', $lines[0]);
		$this->_input[] = explode(',', $lines[1]);

		//$this->_input = InputLoader::LoadAsArrayOfLines($file);
		//$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
		//$this->_input = InputLoader::LoadAsTextBlob($file);
	}

}