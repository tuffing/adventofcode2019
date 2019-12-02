<?php 
class Day02 implements iDay
{
	private $_input;

	public function __construct($input) {
		$this->setInput($input);
	}

	public function runPart1() {
		$this->_input[1] = 12;
		$this->_input[2] = 2;
		$input = $this->runProgram($this->_input);

		return $input[0];
	}

	public function runPart2() {
		$input = $this->_input;

		for ($noun = 0; $noun <= 99; $noun++) {
			for ($verb = 1; $verb <= 99; $verb++) {
				$input = $this->_input;
				$this->_input[1] = $noun;
				$this->_input[2] = $verb;
				$input = $this->runProgram($this->_input);

				if ($input[0] == 19690720) {
					return 100 * $noun + $verb;
				}
			}
		}

		return "fail";
	}

	private function runProgram($input) {
		$i = 0;
		while ($i < count($input)) {
			if ($input[$i] == 99) {
				return $input;
			}
			else if ($input[$i] == 1) {
				$input[$input[$i+3]] = $input[$input[$i+1]] + $input[$input[$i+2]];
			}
			else if ($input[$i] == 2) {
				$input[$input[$i+3]] = $input[$input[$i+1]] * $input[$input[$i+2]];
			}
			$i += 4;
		}

		return $input;
	}

	public function setInput($input) {
		$file = "Day02/".$input;
		$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
	}

}