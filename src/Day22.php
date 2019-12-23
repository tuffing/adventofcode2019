<?php 
class Day22 implements iDay
{
	private $_input;
	private $_cards = [];
	private $_empty = [];
	public function __construct($input) {
		$this->setInput($input);
	}

	public function invert($n, $cards) {
		return gmp_invert((string)$n,  (string)$cards);
	}

	public function get($offset, $increment, $i) {
        return ($offset + $i * $increment) % $cards;
    }

	public function runPart2() {
		$cards = 119315717514047;
		$repeats = 101741582076661;
		$lines = $this->_input;

    	$increment_mul = 1;

   		$offset_diff = 0;

	    foreach($lines as $line)  {
	        if ($line == "deal into new stack") {
	            # reverse sequence.
	            # instead of going up, go down.
	            $increment_mul *= -1;
	            $increment_mul %= $cards;
	            # then shift 1 left
	            $offset_diff += $increment_mul;
	            $offset_diff %= $cards;
	        }
	        else if (strpos($line, 'cut') === 0) {
	        	$parts = explode(' ', $line);
	            $q = $parts[1];
	            # shift q left
	            $offset_diff += $q * $increment_mul;
	            $offset_diff %= $cards;
	        }
	        else if (strpos($line, "deal with increment") === 0) {
	        	$parts = explode(' ', $line);
	        	$q = $parts[3];
	            # difference between two adjacent numbers is multiplied by the
	            # inverse of the increment.
	            $increment_mul *= $this->invert($q, $cards);
	            $increment_mul %= $cards;
	        }
        }


        # calculate (increment, offset) for the number of iterations of the process
        # increment = increment_mul^iterations
        $increment = gmp_powm((string)$increment_mul, (string)$repeats, (string)$cards); //pow(increment_mul, iterations, cards);
        # offset = 0 + offset_diff * (1 + increment_mul + increment_mul^2 + ... + increment_mul^iterations)
        # use geometric series.
        $offset = $offset_diff * (1 - $increment) * $this->invert((1 - $increment_mul) % $cards, $cards);
        $offset %= $cards;

		return  ($offset + 2020 * $increment) % $cards;
	}

	public function runPart1() {
		 return $this->shuffle(10007, 2019, true);
	}

	public function shuffle($size, $pos, $card_pos = false) {
		$deal = $this->_input;

		if (!$this->_cards) {
			$cards = [];
			$empty = [];
			for ($i = 0; $i < $size; $i++) {
				$cards[] = $i;
				$empty[] = -1;
			}
			$this->_empty = $empty;
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
			//print($cards[$pos] . "\n");
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
		//print('????');
		return $cards[$pos];

	}


	public function setInput($input) {
		//parse input
		$file = "Day22/".$input;
		$this->_input = InputLoader::LoadAsArrayOfLines($file);
		//$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
		//$this->_input = InputLoader::LoadAsTextBlob($file);
	}

}