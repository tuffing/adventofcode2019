<?php 
class Day24 implements iDay
{
	private $_input;

	public function __construct($input) {
		$this->setInput($input);
	}

	public function runPart1() {
		$set = new \Ds\Set();

		$map = $this->_input;
		$new = $map;

		/*
		Each minute, The bugs live and die based on the number of bugs in the four adjacent tiles:

		    A bug dies (becoming an empty space) unless there is exactly one bug adjacent to it.
		    An empty space becomes infested with a bug if exactly one or two bugs are adjacent to it.

		Otherwise, a bug or empty space remains the same. (Tiles on the edges of the grid have fewer than four adjacent tiles; the missing tiles count as empty space.) 
		*/
		for ($i=0; $i < 1000; $i++) { 
			$coords = [[0,-1],[0,1],[-1,0],[1,0]];//up down left right;
			foreach ($map as $y => $row) {
				foreach ($row as $x => $cell) {
					$bugs = 0;
					foreach ($coords as $c) {
						$cx = $x + $c[0];
						$cy = $y + $c[1];

						if ($cx < 0 || $cy < 0 || $cx >= 5 || $cy >= 5)
							continue;

						if ($map[$cy][$cx] == '#')
							$bugs ++;
					}

					$new_cell = '.';
					if ($cell == '#' && $bugs == 1) {
						$new_cell = '#';
					}
					else if ($cell == '.' && in_array($bugs, [1,2])) {
						$new_cell = '#';
					}

					$new[$y][$x] = $new_cell;
				}
			}

			$map = $new;

			$hash = '';
			foreach ($map as $row) {
				$hash .= implode('',$row);
			}
			if ($set->contains($hash)) {
				$pow = 0;
				$total = 0;
				foreach ($map as $y => $row) {
					foreach ($row as $x => $cell) {
						if ($cell == '#') {
							$total += 2**$pow;
						}
						$pow++;
					}
				}


				return $total;
			}

			$set->add($hash);
		}

		return 1;
	}


	/**
	* Nest within nest with in nest
	**/
	public function runPart2($steps = 200) {
		$map = $this->_input;
		$new_layer = array_fill(0, 5, array_fill(0, 5, '.'));
		$layers = [-1 => $new_layer, 0 => $map, 1 => $new_layer];
		$new = $layers;
		$middle = 2;

		/*
		Each minute, The bugs live and die based on the number of bugs in the four adjacent tiles:

		    A bug dies (becoming an empty space) unless there is exactly one bug adjacent to it.
		    An empty space becomes infested with a bug if exactly one or two bugs are adjacent to it.

		Otherwise, a bug or empty space remains the same. (Tiles on the edges of the grid have fewer than four adjacent tiles; the missing tiles count as empty space.) 
		*/
		print("STEPS $steps \n");
		for ($i=0; $i < $steps; $i++) { 
			$count = 0;

			$coords = [[0,-1],[0,1],[-1,0],[1,0]];//up down left right;
			foreach ($layers as $z => $map) {
				foreach ($map as $y => $row) {
					foreach ($row as $x => $cell) {
						if ($y == $middle && $x == $middle) {
							continue;
						}
						$bugs = 0;
						foreach ($coords as $c) {
							$cx = $x + $c[0];
							$cy = $y + $c[1];
							$cz = $z;

							if ($cx < 0 || $cy < 0 || $cx >= 5 || $cy >= 5) {
								//go up a level
								$cz--;
								$cx = $middle + $c[0];
								$cy = $middle + $c[1];

								if (!array_key_exists($cz, $layers)) {
									continue;
								}	
							} 
							else if ($cx == $middle && $cy == $middle) {
								//down a level
								$cz++;
								if (!array_key_exists($cz, $layers)) {
									continue;
								}								

								if ($y == $middle -1) {
									//above - top row
									$li = $layers[$cz][0];
								}
								else if ($y == $middle +1) {
									//below - bottom row
									$li = $layers[$cz][4];
								}
								else if ($x == $middle - 1) {
									//left, left column
									$li = [$layers[$cz][0][0], $layers[$cz][1][0],$layers[$cz][2][0],$layers[$cz][3][0],$layers[$cz][4][0]];
								}
								else if ($x == $middle + 1) {
									//right, right colum
									$li = [$layers[$cz][0][4], $layers[$cz][1][4],$layers[$cz][2][4],$layers[$cz][3][4],$layers[$cz][4][4]];
								}
								else {
									die("something wrong \n");
								}

								foreach ($li as $bb) {
									if ($bb == '#')
										$bugs++;
								}

								continue;
							}			

							if ($layers[$cz][$cy][$cx] == '#')
								$bugs ++;
						}

						$new_cell = '.';
						if ($cell == '#' && $bugs == 1) {
							$new_cell = '#';
							$count++;
						}
						else if ($cell == '.' && in_array($bugs, [1,2])) {
							$new_cell = '#';
							$count++;
						}

						$new[$z][$y][$x] = $new_cell;

						//initalise the next layer for prep
						if ($new_cell == '#' && $z == array_key_first($new)) {
							//print("TEST $z");
							$nz = $z - 1;
							$new[$nz] = $new_layer;
							ksort($new);
						}

						//initalise the next layer for prep
						if ($new_cell == '#' && $z == array_key_last($new)) {
							$nz = $z + 1;
							$new[$nz] = $new_layer;
							ksort($new);
						}
					}
				}

			}

			$layers = $new;
			ksort($layers);
		}

		return $count;
	}

	public function setInput($input) {
		//parse input
		$file = "Day24/".$input;
		$lines = InputLoader::LoadAsArrayOfLines($file);
		$input = [];

		foreach ($lines as $row) {
			$input[] = str_split($row);
		}

		$this->_input = $input;

		//$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
		//$this->_input = InputLoader::LoadAsTextBlob($file);
	}

}