<?php 
class Day22 implements iDay
{
	private $_input;
	private $_cards = [];
	private $_empty = [];
	public function __construct($input) {
		$this->setInput($input);
	}

	public function runPart1() {
		return $this->shuffle(10007, 2019, true);
	}

	public function shuffle($size, $pos, $card_pos = false) {
		$deal = $this->_input;

		if (!$this->_cards) {
			print("INIT NEW \n");
			$cards = [];
			$empty = [];
			for ($i = 0; $i < $size; $i++) {
				$cards[] = $i;
				$empty[] = -1;
			}
			$this->_empty = [];
		}
		else {
			$cards = $this->_cards;
			$empty = $this->_empty;
		}


		foreach ($deal as $move) {
			$parts = explode(' ', $move);
			//print("$move \n");

			if (count($parts) == 2) {
				$p1 = array_slice($cards, 0, $parts[1]);
				$cards = array_merge(array_slice($cards, $parts[1]), $p1);
			}
			else if ($parts[2] == 'new') {
				$cards = array_reverse($cards);
			}
			else if ($parts[2] == 'increment') {
				$new_cards = $empty;
				$cards = array_reverse($cards);
				$p = 0;
				while ($cards) {
					if ($new_cards[$p] != -1) {
						die("$p taken");
					}
					$new_cards[$p] = array_pop($cards);
					$p += $parts[3];
					$p = $p % count($new_cards);
					//print($p . "\n");
				}
				$cards = $new_cards;
			}
			else {
				die("something went wrong");
			}
		}

		$this->_cards = $cards;

		if ($card_pos) {
			foreach ($cards as $key => $value) {
				if ($value == $pos)
					return $key;
				# code...
			}
			return -1;
		}
		print('????');
		return $cards[$pos];

	}

	public function runPart2() {
		return 2;
	}

	public function setInput($input) {
		//parse input
		$file = "Day22/".$input;
		$this->_input = InputLoader::LoadAsArrayOfLines($file);
		//$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
		//$this->_input = InputLoader::LoadAsTextBlob($file);
	}

}