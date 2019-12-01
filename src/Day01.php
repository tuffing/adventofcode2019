<?php 
class Day01 implements iDay
{
	private $_input;

	public function __construct($input) {
		$this->setInput($input);
	}

	public function runPart1() {
		$total = 0;
		foreach ($this->_input as $value) {
			$total += floor($value / 3) - 2;
		}
		return $total;
	}

	public function runPart2() {
		$total = 0;
		foreach ($this->_input as $value) {
			while ($value > 5) {
				$value = floor($value / 3) - 2;
				$total += $value;
			}
		}
		return $total;
	}

	public function setInput($input) {
		//parse input
		$file = "Day01/".$input;
		$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
	}

}