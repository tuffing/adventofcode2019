<?php 
class Day05 implements iDay
{
	private $_input;

	public function __construct($input) {
		$this->setInput($input);
	}

	public function runPart1() {
		$output = $this->runProgram($this->_input, 1);

		return $output;
	}

	public function runPart2() {
		return $this->runProgram($this->_input, 5);
	}

	private function runProgram($input, $cmp_input) {
		$i = 0;
		while ($i < count($input)) {
			//get op code
			if ($input[$i] > 10) {
				$op = (int)substr((string)$input[$i], -2);
			}
			else {
				$op = $input[$i];
			}

			//ops
			if ($op == 99) {
				return $output;
			}
			else if ($op === 1) {
				$params = $this->genParams($input[$i], 3, $i, $input);
				$input[$input[$i+3]] = $params[0] + $params[1];
				$i += 4;
			}
			else if ($op === 2) {
				$params = $this->genParams($input[$i], 3, $i, $input);
				$input[$input[$i+3]] = $params[0] * $params[1];
				$i += 4;
			}
			else if ($op === 3) { //input
				//take input. On this exercise it's always the same per command
				$input[$input[$i+1]] = $cmp_input;
				$i += 2;
			}
			else if ($op === 4) { //output mode
				$params = $this->genParams($input[$i], 1, $i, $input);

				$output = $params[0];

				$i += 2;
			}
			else if ($op === 5) {
				// Opcode 5 is jump-if-true: if the first parameter is non-zero, it sets the instruction pointer to the value from the second parameter. Otherwise, it does nothing.
				$params = $this->genParams($input[$i], 2, $i, $input);

				if ($params[0] != 0) {
					$i = $params[1];
				}
				else {
					$i += 3;
				}
				
			}
			else if ($op === 6) {
		    	//Opcode 6 is jump-if-false: if the first parameter is zero, it sets the instruction pointer to the value from the second parameter. Otherwise, it does nothing.
				$params = $this->genParams($input[$i], 2, $i, $input);

				if ($params[0] == 0) {
					$i = $params[1];
				}
				else {
					$i += 3;
				}    	
				
			}
			else if ($op === 7) {
		    	//Opcode 7 is less than: if the first parameter is less than the second parameter, it stores 1 in the position given by the third parameter. Otherwise, it stores 0.
				$params = $this->genParams($input[$i], 3, $i, $input);

				$input[$input[$i+3]] = $params[0] < $params[1] ? 1 : 0;
				
				$i += 4;
			}
			else if ($op === 8) {
		    	//Opcode 8 is equals: if the first parameter is equal to the second parameter, it stores 1 in the position given by the third parameter. Otherwise, it stores 0.
				$params = $this->genParams($input[$i], 3, $i, $input);

				$input[$input[$i+3]] = $params[0] === $params[1] ? 1 : 0;
				
				$i += 4;
				
			}
			else {
				exit("unknown command ". $input[$i]);
			}
		}

		return $output;
	}

	private function genParams($op, $count, $index, $input) {
		$val = str_pad($op, $count+2, '0', STR_PAD_LEFT);

		$codes = str_split($val);
		array_pop($codes);
		array_pop($codes);

		$params = [];
		for ($i = 1; $i <= $count; $i++) {
			$p = array_pop($codes);
			if ($p == 0) {
				$params[] = (int)$input[$input[$index+$i]];
			}
			else {
				$params[] = (int)$input[$index+$i];
			}
		}


		return $params;
	}

	public function setInput($input) {
		$file = "Day05/".$input;
		$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
	}

}