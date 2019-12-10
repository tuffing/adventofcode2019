<?php 
class Day10 implements iDay
{
	private $_input;
	private $_part2_input;

	public function __construct($input) {
		$this->setInput($input);
	}

	public function runPart1() {
		$map = $this->_input;

		$points = [];

		foreach ($map as $keyY => $row) {
			foreach ($row as $keyX => $value) {
				if ($value == '#') {
					$points["$keyX,$keyY"] = [$keyX,$keyY];
				}
			}
			# code...
		}
		//var_dump($points);
		$max = 0;
		$max_order = [];
		$max_center = [];

		foreach ($points as $key => $c1) {
			$done = new \Ds\Set();
			$order = [];
			$total = 0;

			foreach ($points as $key2 => $c2) {
				if ($key == $key2)
					continue;

				$dist =  abs($c1[0] - $c2[0]) + abs($c1[1] - $c2[1]); 

				$gradient = '';


				//if we label these gradients right, then we can sort them alphabetically to get part 2
				if ($c2[1] < $c1[1] && $c2[0] - $c1[0] == 0) {
					$gradient = 'a'; //straight up
				}
				else if ($c2[1] < $c1[1] && $c2[0] > $c1[0]) {
					$gradient = 'ab'; //top right
				}
				else if ($c2[0] > $c1[0] && $c2[1] - $c1[1] == 0 ) {
					$gradient = 'b'; //straight right;
				}
				else if ($c2[1] > $c1[1] && $c2[0] > $c1[0]) {
					$gradient = 'bb'; 	//bottom right
				}
				else if ($c2[1] > $c1[1] && $c2[0] - $c1[0] == 0) {
					$gradient = 'c'; //straight down
				}
				else if ($c2[1] > $c1[1] && $c2[0] < $c1[0]) {
					$gradient = 'cb'; //down left
				}
				else if ($c2[0] < $c1[0] && $c2[1] - $c1[1] == 0 ) {
					$gradient = 'd';//stragiht left
				}
				else {
					$gradient = 'db'; //up left
				}


				if (($c2[0] - $c1[0]) != 0 && ($c2[1] - $c1[1]) != 0) {
					$g = ($c2[1] - $c1[1]) / ($c2[0] - $c1[0]);

					if ($gradient == 'ab' || $gradient == 'cb') {
						//for the benefit of part 2, invert these gradient numbers
						//this will allow us to sort to get the answer
						$gradient.= str_pad(number_format(100-($g *-1), 3), 10, '0', STR_PAD_LEFT);
					}
					else {
						$gradient.=str_pad(number_format($g, 3), 10, '0', STR_PAD_LEFT);
					}
 				}


				if (!$done->contains($gradient)) {
					$total++;
					$done->add($gradient);
					$order[$gradient] = $c2;
				}
				else {
					//Manhattandistance=abs(x1−x2)+abs(y1−y2)
					$old_dist = abs($c1[0] - $order[$gradient][0]) + abs($c1[1] - $order[$gradient][1]);
					if ($dist < $old_dist) {
						$order[$gradient] = $c2;
					}
				}
			}
			$max = max($total, $max);
			if ($total == $max) {
				$max_order = $order;
				$max_center = $c1;
			}

		}

		//set up the variable for part 2.
		//if the gradient naming is right, then ksort will sort these clock wise.
		ksort($max_order);
		$this->_part2_input = $max_order;


		return $max;

		
	}

	private function printMap($map) {
		print(PHP_EOL. '-----' . PHP_EOL);
		foreach ($map as $row) {
			print (implode(',', $row) . PHP_EOL);
		}
		print('-----' . PHP_EOL);		
	}

	public function runPart2() {
		if (count($this->_part2_input) >= 200) {
			$values = array_values($this->_part2_input);
			return $values[199][0] * 100 + $values[199][1];
		}
	}

	public function setInput($input) {
		//parse input
		$file = "Day10/".$input;
		$rows = InputLoader::LoadAsArrayOfLines($file);

		$this->_input = array_map('str_split', $rows);
		


		//$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
		//$this->_input = InputLoader::LoadAsTextBlob($file);
	}

}