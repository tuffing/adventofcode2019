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

				$input[$key1]['x'] += $input[$key1]['rx']; 
				$input[$key1]['y'] += $input[$key1]['ry']; 
				$input[$key1]['z'] += $input[$key1]['rz']; 
			}

			//print($input[0]['x'].",".$input[0]['y'].",".$input[0]['z'].",".$input[0]['rx'].",".$input[0]['ry'].",".$input[0]['rz']. "\n");
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
		return 2;
	}

	public function setInput($input) {
		//parse input
		$file = "Day12/".$input;
		//$mapping = [
		//	1 => ['name' => 'x', 'type' => 'int'],
		//	3 => ['name' => 'y', 'type' => 'int'],
		//	5 => ['name' => 'z', 'type' => 'int'],
		//];
		//$extras = ['rx' => 0, 'ry' => 0, 'rz' => 0];
		//$this->_input = InputLoader::LoadAsArrayOfObjects($file, $mapping, $extras);

		//var_dump($this->_input);
		//$this->_input = InputLoader::LoadAsArrayOfLines($file);
		$numbers = InputLoader::FindAndLoadAllNumbersIntoArray($file);
		$this->_input = [
			['x' => $numbers[0], 'y' => $numbers[1], 'z' => $numbers[2], 'rx' => 0, 'ry' => 0, 'rz' => 0],
			['x' => $numbers[3], 'y' => $numbers[4], 'z' => $numbers[5], 'rx' => 0, 'ry' => 0, 'rz' => 0],
			['x' => $numbers[6], 'y' => $numbers[7], 'z' => $numbers[8], 'rx' => 0, 'ry' => 0, 'rz' => 0],
			['x' => $numbers[9], 'y' => $numbers[10], 'z' => $numbers[11], 'rx' => 0, 'ry' => 0, 'rz' => 0]
		];
		//var_dump($this->_input);
		//$this->_input = InputLoader::LoadAsTextBlob($file);
	}

}