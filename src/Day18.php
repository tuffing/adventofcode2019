<?php 
class Day18 implements iDay
{
	private $_input;
	private $_p1 = 5609; //PHP_INT_MAX;
	private $_hash = [];

	public function __construct($input) {
		$this->setInput($input);
	}

	public function runPart1() {
		//find the @..
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

		//print(ord('A') . ' ' . ord('Z') . "\n");
		//$this->find_path2($sx, $sy, 0, []);
		$this->find_path2($sx, $sy, 0, []);


		//print("start is $sx $sy");
		return $this->_p1;
	}

	public function find_path2($sx, $sy, $steps, $keys_visited) {
		
		$start_letters = $this->findLetters($sx, $sy);
		//return 0;
		foreach($start_letters as $l => $cont) {
			$letters = $this->findLetters($cont['x'], $cont['y']);
			$start_letters[$l]['distances'] = $letters;
		}

		//var_dump($start_letters);
		//return;
		$start_letters['@'] = ['distances' => $start_letters];
		//$test = array_reverse($start_letters);
		//var_dump($start_letters);

		$this->recursePart2($start_letters, 0, ['@' => 0], '@');
		return;
		
		$queue = new \Ds\PriorityQueue();
		$queue->push(['letter' => '@', 'keys' => [], 'hash' => 0, 'last' => ''], 0);
		$visited = ['@' => 0];
		$visited_key_hash = ['@', []];
		//$visited_maxed_count = ['@' => count(letters)];

		$min = 6000;
		while (count($queue)) {
			$curr = $queue->pop();
			$letter = $curr['letter'];
			$keys = $curr['keys'];
			$hash = $curr['hash'];
			$last = $curr['last'];

			foreach ($start_letters[$letter]['distances'] as $l => $value) {
				if (array_diff($value['doors'], $keys)) {
					//we don't have enough keys yet
					if ($l == 'e' && $letter == 'd') {
						print("NOOO-- $letter -> $l \n" );
						var_dump($keys);
						var_dump(array_diff($value['doors'], $keys));
					}
					continue;
				}

				if (!isset($visited[$l]) || $visited[$letter] + $value['s'] <= $visited[$l]){
					// || (count($visited_key_hash[$l]) < count($hash)) && count($hash) < count($start_letters)) {
					$nkeys = $keys;
					$nkeys[] = $l;
					//sort($nkeys);
					$new_item = ['letter' => $l, 'keys' => $nkeys, 'hash' => $nkeys, 'last' => $letter];
					if (!isset($visited[$l]) || $visited[$letter] + $value['s'])
						$visited[$l] = $visited[$letter] + $value['s'];
					$visited_key_hash[$l] = $nkeys;
					$queue->push($new_item, count($nkeys));//$visited[$letter] + $value['s']);
				}
				
			}
		}

		//print(max(array_values($visited)));
		//var_dump($visited);

		$this->_p1 = max(array_values($visited));

	}	

	public function recursePart2(&$start_letters, $steps, $visited, $letter) {
		/*if ( ($steps == 6 && $letter == $this->_x )) {
			$ol = array_keys($visited);
			sort($ol);

			if ($this->_hash == implode("", $ol)) {
				return;
			}

		}*/
		if ($steps >= $this->_p1)// || ($steps > $this->_p1 /2 && count($visited) > count($start_letters) / 2))
			return;

		if (count($visited) == count($start_letters)) {
			//var_dump($visited);
			print($steps . "|". count($start_letters)."\n");
			$this->_p1 = min($this->_p1, $steps);
			//$ol = array_keys($visited);
			//$ol = array_slice($ol, 0, 6);
			//sort($ol);
			//$this->_hash = implode('',array_keys($visited));
			//$this->_x = array_values($visited)[6];
			return;
		}

		$to_visit = [];
		$keys = array_keys($visited);
		//while (count($queue)) {
			/*$curr = $queue->pop();
			$letter = $curr['letter'];
			$keys = $curr['keys'];
			$hash = $curr['hash'];
			$last = $curr['last'];*/

			foreach ($start_letters[$letter]['distances'] as $l => $value) {
				if (array_diff($value['doors'], $keys)) {
					//we don't have enough keys yet
					/*if ($l == 'e' && $letter == 'd') {
						print("NOOO-- $letter -> $l \n" );
						var_dump($keys);
						var_dump(array_diff($value['doors'], $keys));
					}*/
					continue;
				}

				if (!isset($visited[$l]) || $visited[$letter] + $value['s'] <= $visited[$l]){
					// || (count($visited_key_hash[$l]) < count($hash)) && count($hash) < count($start_letters)) {
					$nkeys = $keys;
					$nkeys[] = $l;
					//sort($nkeys);
					//$new_item = ['letter' => $l, 'keys' => $nkeys, 'hash' => $nkeys, 'last' => $letter];
					//if (!isset($visited[$l]) || $visited[$letter] + $value['s'])
					$visited_new = $visited;
					$visited_new[$l] = $visited_new[$letter] + $value['s'];
					//$queue->push($new_item, count($nkeys));//$visited[$letter] + $value['s']);

					$ol = array_keys($visited);
					sort($ol);

					$hash = $l. '|' . implode("", $ol);

					//print($hash . "\n");

					if (isset($this->_hash[$hash]) && $this->_hash[$hash] <= $visited_new[$l]) {
						if (count($visited_new) == count($start_letters)) {
							$this->_p1 = min($this->_p1, $visited_new[$l]);
						}

						return;
					}

					$this->_hash[$hash] = $visited_new[$l];

					if ($visited_new[$l] > $this->_p1) {
						return;
					}

					$this->recursePart2($start_letters, $visited_new[$l], $visited_new, $l);
				}
				
			}
		//}

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
					$visited["$cx,$cy"]['s'] >= $visited["$x,$y"]['s'] + 1) ||
					array_diff($visited["$cx,$cy"]['doors'],$visited["$x,$y"]['doors'])
					)
					 && isset($this->_input[$cy]) && isset($this->_input[$cy][$cx])) {
					$ord = ord($this->_input[$cy][$cx]);

					/*if ($ord >= $min_key && $ord <= $max_key && isset($visited["$cx,$cy"]) && array_diff($visited["$cx,$cy"]['doors'],$visited["$x,$y"]['doors'])) {
						print($this->_input[$cy][$cx] . " dammit ". implode("", $visited["$cx,$cy"]['doors']) . " | " . implode("", $visited["$x,$y"]['doors']));
						return;
					}*/

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

	public function find_path($sx, $sy, $steps, $keys_visited) {
		//print("visited". count($keys_visited). "\n");
		//print("start $sx $sy $steps\n");
		$locked = 0;
		$min_key = ord('a');
		$max_key = ord('z');

		$min_door = ord('A');
		$max_door = ord('Z');
		//print($min_key . ' ' . $max_door . ' ' . "\n");
		$keys_found = [];
		$dead_ends = [];

		$visited = ["$sx,$sy" => 0];
		$coords = [[0,-1], [0, 1], [-1,0],[1,0]]; //up, down, left, right
		$queue = new \Ds\Queue([[$sx,$sy]]);

		while (count($queue)) {
			$curr = $queue->pop();
			$x = $curr[0];
			$y = $curr[1];

			foreach ($coords as $c) {
				$cx = $x + $c[0];
				$cy = $y + $c[1];

				if ((!isset($visited["$cx,$cy"]) || $visited["$cx,$cy"] > $visited["$x,$y"] + 1) && isset($this->_input[$cy]) && isset($this->_input[$cy][$cx])) {
					$ord = ord($this->_input[$cy][$cx]);


					if (!($this->_input[$cy][$cx] == '@' || $this->_input[$cy][$cx] == '.'  || ($ord  >= $min_door && $ord <= $max_key))) {
						//print($map[$cy][$cx] . "\n");

						continue;
					}

					//check for locked doors
					if ($ord >= $min_door && $ord <= $max_door && !in_array(strtolower($this->_input[$cy][$cx]), $keys_visited)) {
						//print("locked!\n");
						$locked++;
						continue;
					}

					$visited["$cx,$cy"] = $visited["$x,$y"] + 1;


					if ($ord >= $min_key && $ord <= $max_key && !in_array($this->_input[$cy][$cx], $keys_visited)) {
						//print("hello");

						$keys_found[] = [$cx,$cy];
						$cc = 0;
						/*foreach($coords	as $j => $c) {
							if (isset($this->_input[$cy + $c[1]]) && isset($this->_input[$cy + $c[1]][$cx + $c[0]]) && $this->_input[$cy + $c[1]][$cx + $c[0]] == '#') {
								$cc++;
								//print("$cc \n");
							}

							if ($j - $cc > 1 ) {
								continue;
							}
						}

						if ($cc == 3) {
							$dead_ends[] = [$cx,$cy];
						}*/
					}
					else {
						$queue->push([$cx,$cy]);
					}
				}
			}

			if ($visited["$x,$y"]+1 >= $this->_p1) {
				return;
			}
		}

		if (count($keys_found) == 0 && $locked == 0) {
			$this->_p1 = min($steps, $this->_p1);
			print("found ". $this->_p1 . "\n");
		}

		if (!$keys_found || $steps >= $this->_p1) { 
			if ($steps >= $this->_p1) {
				print("bailing on $steps");
			}

			return;

		}
		//if (count($dead_ends)) {
	//		$keys_found = $dead_ends;
			/*$keys_found = [];
			$closet_p = [];
			$closest = 10000;
			foreach ($dead_ends as $k => $v) {
				if (abs($x-$v[0]) + abs($y-$v[1]) < $closest ) {
					$closest = abs($x-$v[0]) + abs($y-$v[1]);
					$closet_p = $v;
				}
			}
			$keys_found[] = $closet_p;*/
			//var_dump($keys_found);
			//return;
	//	}
//
		foreach ($keys_found as $found) {
			$new_visited = $keys_visited;
			$nx = $found[0];
			$ny = $found[1];
			if ($steps + $visited["$nx,$ny"] >= $this->_p1) {
				continue;
			}

			$ol = $keys_visited;
			sort($ol);

			$hash = $this->_input[$ny][$nx]. '|' . implode("", $ol);

			//print($hash . "\n");

			if (isset($this->_hash[$hash]) && $this->_hash[$hash] < $steps + $visited["$nx,$ny"]) {
				//if (count($visited_new) == count($start_letters)) {
			//		$this->_p1 = min($this->_p1, $visited_new[$l]);
			//	}
				//print("?");
				return;
			}
			$this->_hash[$hash] = $steps + $visited["$nx,$ny"];


			$new_visited[] = $this->_input[$ny][$nx];
			$this->find_path($nx, $ny, $steps + $visited["$nx,$ny"], $new_visited);
			# code...
		}

	}

	public function runPart2() {
		return 2;
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