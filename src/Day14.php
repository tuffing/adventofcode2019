<?php 
class Day14 implements iDay
{
	private $_input;
	private $_reqs;
	private $_fuel;

	public function __construct($input) {
		$this->setInput($input);
	}

	public function runPart1() {
		return 2;
		$input = $this->_input;
		$reqs = $input['FUEL']['reqs'];
		$avail = [];

		$ore = 0;
		for($i = 0; $i< 10000; $i++) {
			$new_req = [];
			$met = 0;
			//var_dump($reqs);

			foreach ($reqs as $key => $value) {
				if (isset($avail[$key])) {
					$value -= $avail[$key];
				}

				$orig_value = $value;


				if ($value > 0) {
					$count_needed = ceil($value / $input[$key]['output']) * $input[$key]['output'];

					$value -= $count_needed;

					foreach ($input[$key]['reqs'] as $rrkey => $rrval) {
						$n = $rrval * ceil($orig_value / $input[$key]['output']);

						if ($rrkey == 'ORE') {
							print("$key - $orig_value buying or e$n \n");
							$ore += $n;
						}
						else {
							if (!isset($new_req[$rrkey])) 
								$new_req[$rrkey] = 0;
							$new_req[$rrkey] += $n;
						}
					}
				}

				$avail[$key] = ($value == 0 ? 0 : $value * -1);
			}
			$reqs = $new_req;
			if (!$reqs)
				break;
		}
		$this->ore = $ore;
		return $ore;
	}

	public function runPart2() {
		$input = $this->_input;
		$avail = [];
		$avail['ORE'] = 1000000000000;


		//theortically if i can calculate the ultimate per unit cost for each part of fuel i could speed this up significantly.

		$ore = 0;
		$fuel = -1;
		while ($avail['ORE'] > 0) {
			$reqs = $input['FUEL']['reqs'];

			for($i = 0; $i< 10000; $i++) {
				$new_req = [];
				$met = 0;

				foreach ($reqs as $key => $value) {
					if (isset($avail[$key])) {
						$value -= $avail[$key];
					}

					$orig_value = $value;


					if ($value > 0 && $key != 'ORE') {
						$count_needed = ceil($value / $input[$key]['output']) * $input[$key]['output'];
						$value -= $count_needed;
						foreach ($input[$key]['reqs'] as $rrkey => $rrval) {
							$n = $rrval * ceil($orig_value / $input[$key]['output']);

							if ($rrkey == 'ORE') {
								$ore += $n;
							}
							if (!isset($new_req[$rrkey])) 
								$new_req[$rrkey] = 0;
							$new_req[$rrkey] += $n;
						}
					}

					$avail[$key] = ($value == 0 ? 0 : $value * -1);
				}

				$reqs = $new_req;

				if (!$reqs)
					break;
			}
			$fuel ++;


			if ($fuel % 100000 == 0) {
				print("$fuel \n");
			}
		}
		return $fuel;
	}

	public function setInput($input) {
		//parse input
		$file = "Day14/".$input;
		$this->_input = InputLoader::LoadAsArrayOfLines($file);
		$this->parse($this->_input);
		//$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
		//$this->_input = InputLoader::LoadAsTextBlob($file);
	}


	private function parse() {
		$requirement = [];
		foreach ($this->_input as $key => $value) {
			//(\d+) (\w)
			preg_match_all('/(\d+) (\w+)/', $value, $matches);
			$out = array_pop($matches[2]);
			$requirements[$out] = ['output' => (int)array_pop($matches[1]), 'reqs' => []];

			while (count($matches[2])) {
				$requirements[$out]['reqs'][array_shift($matches[2])] = (int)array_shift($matches[1]); 
			}

		}
		$this->_input = $requirements;
	}
}