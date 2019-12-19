<?php 
class Day18 implements iDay
{
	private $_input;
	private $_p1 = PHP_INT_MAX;
	private $_p2 = PHP_INT_MAX;
	private $_hash = [];

	public function __construct($input) {
		$this->setInput($input);
	}

	public function runPart1() {
		$sy = 0;
		$sx = 0;
		foreach ($this->_input as $y => $row) {
			if ($x = array_search('@', $row)) {
				$sx = $x;
				$sy = $y;
				break;
			}
		}

		$map = $this->_input;

		$this->find_path($sx, $sy, 0, []);
		return $this->_p1;
	}

	public function find_path($sx, $sy, $steps, $keys_visited) {
		$start_letters = $this->findLetters($sx, $sy);

		foreach($start_letters as $l => $cont) {
			$letters = $this->findLetters($cont['x'], $cont['y']);
			$start_letters[$l]['distances'] = $letters;
		}

		$start_letters['@'] = ['distances' => $start_letters];
		$this->recursePart1($start_letters, 0, ['@' => 0], '@');
		return;
	}	

	public function recursePart1(&$start_letters, $steps, $visited, $letter) {
		if ($steps >= $this->_p1)
			return;

		if (count($visited) == count($start_letters)) {
			$this->_p1 = min($this->_p1, $steps);
			return;
		}

		$to_visit = [];

		$keys = array_keys($visited);
		foreach ($start_letters[$letter]['distances'] as $l => $value) {

			if (array_diff($value['doors'], $keys)) {
				continue;
			}

			if (!isset($visited[$l]) || $visited[$letter] + $value['s'] <= $visited[$l]){
				$nkeys = $keys;
				$nkeys[] = $l;
				$visited_new = $visited;
				$visited_new[$l] = $visited_new[$letter] + $value['s'];

				$ol = $visited_new;
				ksort($ol);
				$hash = $l. '|' . implode("", array_keys($ol));

				if (array_key_exists($hash, $this->_hash) && $this->_hash[$hash] <= $visited_new[$l]) {
					if (count($visited_new) == count($start_letters)) {
						$this->_p1 = min($this->_p1, $visited_new[$l]);
					}
					continue;
				}

				$this->_hash[$hash] = $visited_new[$l];

				if ($visited_new[$l] > $this->_p1) {
					return;
				}

				$this->recursePart1($start_letters, $visited_new[$l], $visited_new, $l);
			}
			
		}
	}

	public function findLetters($sx, $sy) {
		$visited = ["$sx,$sy" => ['s' => 0, 'doors' => []]];
		$coords = [[0,-1], [0, 1], [-1,0],[1,0]]; //up, down, left, right
		$queue = new \Ds\Queue([[$sx,$sy]]);
		$min_key = ord('a');
		$max_key = ord('z');

		$min_door = ord('A');
		$max_door = ord('Z');
		$letters = [];

		while (count($queue)) {
			$curr = $queue->pop();
			$x = $curr[0];
			$y = $curr[1];

			foreach ($coords as $c) {
				$cx = $x + $c[0];
				$cy = $y + $c[1];

				if ((!isset($visited["$cx,$cy"]) || (
					$visited["$cx,$cy"]['s'] >= $visited["$x,$y"]['s'] + 1) )
					 && isset($this->_input[$cy]) && isset($this->_input[$cy][$cx])) {
					$ord = ord($this->_input[$cy][$cx]);

					if ($this->_input[$cy][$cx] == '#') {
						continue;
					}

					$queue->push([$cx,$cy]);

					$visited["$cx,$cy"] = [];
					$visited["$cx,$cy"]['doors'] = $visited["$x,$y"]['doors'];
					$visited["$cx,$cy"]['s'] = $visited["$x,$y"]['s'] + 1;

					//check for  doors
					if ($ord >= $min_door && $ord <= $max_door) {
						$visited["$cx,$cy"]['doors'][] = strtolower($this->_input[$cy][$cx]);
					}

					if ($ord >= $min_key && $ord <= $max_key) {
						$letters[$this->_input[$cy][$cx]] = $visited["$cx,$cy"];
						$letters[$this->_input[$cy][$cx]]['x'] = $cx;
						$letters[$this->_input[$cy][$cx]]['y'] = $cy;
					}
				}
			}
		}
		return $letters;
	}


	public function runPart2() {
		$this->_hash = [];

		$file = "Day18/inputp2.txt";
		//$file = "Day18/test136.txt";
		$lines = InputLoader::LoadAsArrayOfLines($file);
		$this->_input = [];
		foreach ($lines as $value) {
			$this->_input[] = str_split($value);
		}

		$starts = [];
		foreach ($this->_input as $y => $row) {
			$f = array_keys($row, '@');
			if (count($f)) {
				foreach ($f as $x) {
					$sx = $x;
					$sy = $y;
					$starts[] = [$x, $y];
					if (count($starts) == 4)
						break;
				}
			}
		}

		$map = $this->_input;
		$init_letters = [];
		//set up the path
		foreach($starts as $k => $r) {
			$start_letters = $this->findLetters($r[0], $r[1]);

			foreach($start_letters as $l => $cont) {
				$letters = $this->findLetters($cont['x'], $cont['y']);
				$start_letters[$l]['distances'] = $letters;
			}

			$start_letters[$k.'@'] = ['distances' => $start_letters];

			$init_letters[] = $start_letters; 
		}

		$this->recursePart2($init_letters, 0, ['0@' => 0, '1@' => 0, '2@' => 0, '3@' => 0], ['0@', '1@', '2@', '3@'], 0, 26);

		return $this->_p2;
	}

	public function recursePart2(&$letters,$steps, $visited, $curr_letters, $key_count, $total_keys) {
		if ($steps >= $this->_p2)
			return;

		if ($key_count ==  $total_keys) {
			$this->_p2 = min($this->_p2, $steps);
			return;
		}

		$to_visit = [];

		$keys = array_keys($visited);

		foreach ($curr_letters as $key => $letter) {
			$hits = 0;
			foreach ($letters[$key][$letter]['distances'] as $l => $value) {

				if (array_diff($value['doors'], $keys)) {
					continue;
				}

				if (!isset($visited[$l]) || $visited[$letter] + $value['s'] <= $visited[$l]){
					$nkeys = $keys;
					$nkeys[] = $l;
					$visited_new = $visited;
					$visited_new[$l] = $steps + $value['s'];

					$ol = $visited_new;
					ksort($ol);
					$hash = $l. '|' . implode("", array_keys($ol));

					if (array_key_exists($hash, $this->_hash) && $this->_hash[$hash] <= $visited_new[$l]) {
						continue;
					}

					$this->_hash[$hash] = $visited_new[$l];

					if ($visited_new[$l] > $this->_p2) {
						return;
					}
					$new_letters = $curr_letters;
					$new_letters[$key] = $l;

					$this->recursePart2($letters, $visited_new[$l], $visited_new, $new_letters, count($visited_new)-4, $total_keys);
				}
				
			}

		}

	}

	public function setInput($input) {
		//parse input
		$file = "Day18/".$input;
		//$file = "Day18/test136.txt";
		$lines = InputLoader::LoadAsArrayOfLines($file);
		$this->_input = [];
		foreach ($lines as $value) {
			$this->_input[] = str_split($value);
		}
		//$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
		//$this->_input = InputLoader::LoadAsTextBlob($file);
	}

}