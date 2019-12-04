<?php 
class Day04 implements iDay
{
	private $_low;
	private $_high;
	private $_part2;

	public function __construct($input) {
		$this->setInput($input);
	}

	public function runPart1() {
		//processes part 1 an 2 at the same time
		//the double number check is possible in regex and we can always sort the numbers to see if the results match but there is a performance
		//penalty compared to a simple for loop on each - roughly twice as fast to do it this way.
		$values_p1 = 0;
		$values_p2 = 0;
		for ($i = $this->_low; $i <= $this->_high; $i++) {
			$double = false;
			$double_p2 = false;
			$doesnt_decrease = true;
			$straight = 0;

			$value = (string)$i;

			for ($j = 1; $j < 6; $j++) {
				if ($value[$j] < $value[$j-1]) {
					$doesnt_decrease = false;
					break;
				}

				if ($value[$j] == $value[$j-1]) {
					$double = true;
					$straight++;
				}
				else if ($straight == 1) {
					$double_p2 = true;
				}
				else {
					$straight = 0;
				}
			}

			if ($doesnt_decrease && $double) {
				$values_p1++;
			}

			if ($doesnt_decrease && ($double_p2 || $straight == 1)) {
				$values_p2++;
			}

		}

		$this->_part2 = $values_p2;
		return $values_p1;
	}

	public function runPart2() {
		return $this->_part2;
	}

	public function setInput($input) {
		//parse input
		$file = "Day04/".$input;
		$input = explode('-',InputLoader::LoadAsTextBlob($file));
		$this->_low = $input[0];
		$this->_high = $input[1];
	}

}