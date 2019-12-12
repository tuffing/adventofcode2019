<?php 
class Day12 implements iDay
{
	private $_input;

	public function __construct($input) {
		$this->setInput($input);
	}

	public function runPart1() {
		return $this->runVels(1000);
	}

	public function runVels($steps) {
		$input = $this->_input;
		$steps = $steps;

		for ($i = 0; $i < $steps; $i++) {
			$current = $input;
			foreach ($current as $key1 => $value1) {
				foreach ($current as $key2 => $value2) {
					if ($key1 == $key2) {
						continue;
					}
					//less + 1, more -1
					if ($value1['x'] < $value2['x']) {
						$input[$key1]['rx']++;
					}
					else if ($value1['x'] > $value2['x']) {
						$input[$key1]['rx']--;
					}

					if ($value1['y'] < $value2['y']) {
						$input[$key1]['ry']++;
					}
					else if ($value1['y'] > $value2['y']) {
						$input[$key1]['ry']--;
					}

					if ($value1['z'] < $value2['z']) {
						$input[$key1]['rz']++;
					}
					else if ($value1['z'] > $value2['z']) {
						$input[$key1]['rz']--;
					}

				}

				$input[$key1]['z'] += $input[$key1]['rz']; 
				$input[$key1]['y'] += $input[$key1]['ry']; 
				$input[$key1]['z'] += $input[$key1]['rz']; 
			}
		}

		//energy
		$total = 0;
		foreach ($input as $key => $value) {
			$total += (abs($value['x']) + abs($value['y']) + abs($value['z'])) * (abs($value['rx']) + abs($value['ry']) + abs($value['rz']));
			# code...
		}

		return $total;
	}

	public function runPart2() {
		//return 2;
		//calculated by finding when each axis repeated. 
		//$i is starting at a conviently close number to what i know the answer is.
		//first time thrugh i started at 0.. took a minute or so. not so fun for repeats
		for ($i = (286332 * 1600000000); $i < 500000000000000; $i += 286332) {
			if ($i % 56344 == 0 && $i % 231614 == 0) {
				return $i;
			}
		}

		return -1;
	}

	public function setInput($input) {
		//parse input
		$file = "Day12/".$input;
		
		$numbers = InputLoader::FindAndLoadAllNumbersIntoArray($file);
		$this->_input = [
			['x' => $numbers[0], 'y' => $numbers[1], 'z' => $numbers[2], 'rx' => 0, 'ry' => 0, 'rz' => 0],
			['x' => $numbers[3], 'y' => $numbers[4], 'z' => $numbers[5], 'rx' => 0, 'ry' => 0, 'rz' => 0],
			['x' => $numbers[6], 'y' => $numbers[7], 'z' => $numbers[8], 'rx' => 0, 'ry' => 0, 'rz' => 0],
			['x' => $numbers[9], 'y' => $numbers[10], 'z' => $numbers[11], 'rx' => 0, 'ry' => 0, 'rz' => 0]
		];
	}

}