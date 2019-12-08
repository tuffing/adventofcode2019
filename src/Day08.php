<?php 
class Day08 implements iDay
{
	private $_input;

	public function __construct($input) {
		$this->setInput($input);
	}

	public function runPart1() {
		$chars = str_split($this->_input);

		$least_zeros = 1000000;
		$total = 0;

		$grid = ['0' => 0, '1' => 0, '2' => 0];

		$width = 25;
		$height = 6;
		$size = $width * $height;

		for ($i = 0; $i <= count($chars); $i++) {
			if ($i % $size == 0 && $i != 0) {
				if ($grid['0'] < $least_zeros) {
					$least_zeros = $grid['0'];
					$total = $grid['1'] * $grid['2'];
				}

				$grid = ['0' => 0, '1' => 0, '2' => 0];
			}

			if ($i < count($chars)) 
				$grid[$chars[$i]]++;
		}

		return $total;
	}

	public function runPart2() {
		//$input = str_split('0222112222120000');
		$input = str_split($this->_input);

		$image = [];
		$width = 25;
		$height = 6;
		$size = $height * $width;

		//inialise grid
		for ($i=0; $i < $height; $i++) { 
			$image[] = [];
		}

		//build
		$row = 0;
		for($i = 0; $i < count($input); $i++) {
			if ($i % $size == 0 && $i > 0) {
				$row = 0;
			}
			else if ($i % $width == 0 && $i > 0) {
				$row++;
			}

			$x = $i % $width;

			if (!isset($image[$row][$x]) || $image[$row][$x] == '2') {
				$image[$row][$x] = $input[$i];
			}
		}


		//print;
		foreach ($image as $row) {
			$line = implode('', $row);
			$line = str_replace('0', ' ', $line);
			$line = str_replace('1', '#', $line);
			print($line . PHP_EOL);
		}

		return 'See output above';
	}

	public function setInput($input) {
		//parse input
		$file = "Day08/".$input;
		//$this->_input = InputLoader::LoadAsArrayOfLines($file);
		//$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
		$this->_input = InputLoader::LoadAsTextBlob($file);
	}

}