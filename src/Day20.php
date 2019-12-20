<?php 
class Day20 implements iDay
{
	private $_map;
	private $_height;
	private $_width;
	private $_portals = [];

	public function __construct($input) {
		$this->setInput($input);
	}

	public function runPart1() {
		$x = $this->_portals['AA'][0][0];
		$y = $this->_portals['AA'][0][1];

		$visited = ["$x,$y" => 0];
		$queue = new \Ds\Queue();
		$queue->push([$x,$y]);
		$map = $this->_map;

		while(count($queue)) {
			$cur = $queue->pop();
			$x = $cur[0];
			$y = $cur[1];
			$s = $visited["$x,$y"];

			$coords = [[0,-1],[0,1],[-1,0],[1,0]];

			foreach ($coords as $c) {
				$cx = $c[0] + $x;
				$cy = $c[1] + $y;

				if ($map["$cx,$cy"] == '#' || in_array($map["$cx,$cy"], ['AA', 'ZZ']))
					continue;

				if ($map["$cx,$cy"] != '.') {
					foreach ($this->_portals[$map["$cx,$cy"]] as $p) {
						if ($p[0] != $x && $p[1] != 0) {
							$cx = $p[0];
							$cy = $p[1];
						}
					}
				}

				if (!isset($visited["$cx,$cy"]) || $visited["$cx,$cy"] > $s + 1) {
					$visited["$cx,$cy"] = $s + 1;
					$queue->push([$cx,$cy]);
				}
			}

		}

		$fx = $this->_portals['ZZ'][0][0];
		$fy = $this->_portals['ZZ'][0][1];

		return $visited["$fx,$fy"];
	}

	public function runPart2() {
		$x = $this->_portals['AA'][0][0];
		$y = $this->_portals['AA'][0][1];

		$visited = ["$x,$y,0" => 0];
		$level_cap = 30;

		$queue = new \Ds\PriorityQueue();
		$queue->push([$x,$y, 0], $level_cap);
		$map = $this->_map;

		$lowest = 10000000;


		while(count($queue)) {
			$cur = $queue->pop();

			$x = $cur[0];
			$y = $cur[1];
			$l = $cur[2];
			$s = $visited["$x,$y,$l"];

			$coords = [[0,-1],[0,1],[-1,0],[1,0]];

			foreach ($coords as $c) {
				$cx = $c[0] + $x;
				$cy = $c[1] + $y;
				$cl = $l;

				if ($l == 0 && $map["$cx,$cy"] == 'ZZ') {
					$lowest = min($lowest, $visited["$x,$y,0"]);
					continue;
				}

				if ($map["$cx,$cy"] == '#' || in_array($map["$cx,$cy"], ['AA', 'ZZ']))
					continue;

				if ($map["$cx,$cy"] != '.') {
					$edgeW = $this->_width - $cx;
					$edgeH = $this->_height - $cy;
					$pod = $map["$cx,$cy"];

					if ($l > 0 && (in_array(2, [$x,$y]) || $this->_width - $cx <= 2 || $this->_height - $cy <= 2)) {
						$cl = $l - 1;
					}
					else if ($l == 0 && (in_array(2, [$x,$y]) || $this->_width - $cx <= 2 || $this->_height - $cy <= 2)) {
						continue;
					}
					else if ($l <= $level_cap) {
						$cl = $l + 1;
					}
					else {
						continue;

					}

					foreach ($this->_portals[$map["$cx,$cy"]] as $p) {
						if ($p[0] != $x && $p[1] != 0) {
							$cx = $p[0];
							$cy = $p[1];
						}
					}
				}

				if (!isset($visited["$cx,$cy,$cl"]) || $visited["$cx,$cy,$cl"] > $s + 1) {
					$visited["$cx,$cy,$cl"] = $s + 1;
					$queue->push([$cx,$cy, $cl], $level_cap - $cl);
				}
			}

		}

		return $lowest;
	}

	public function setInput($input) {
		//parse input
		$file = "Day20/".$input;
		$map = InputLoader::LoadAsArrayOfLines($file);
		
		$this->_height = count($map);
		$this->_width = strlen($map[0]);
		foreach ($map as $y => $row) {
			$row = str_split($row);
			foreach ($row as $x => $cell) {
				$map["$x,$y"] = $cell;
			} 
		}

		$coords = [[0,-1],[0,1],[-1,0],[1,0]];
		for ($y = 0; $y < $this->_height; $y++) {
			for ($x = 0; $x < $this->_width; $x++) {
				if ($map["$x,$y"] != '.')
					continue;

				foreach ($coords as $c) {
					$cx = $c[0] + $x;
					$cy = $c[1] + $y;
					if (in_array($map["$cx,$cy"], ['.', '#']))
						continue;

					$cx2 = $cx+$c[0];
					$cy2 = $cy+$c[1];
					$name = $map["$cx,$cy"] . $map["$cx2,$cy2"];

					if ($c[0] == -1 || $c[1] == -1) 
						$name = strrev($name);
					
					if (!isset($this->_portals[$name]))
						$this->_portals[$name] = [];

					$this->_portals[$name][] = [$x,$y];
					$map["$cx,$cy"] = $name;

					break;
				}
			}
		}

		$this->_map = $map;

		//$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
		//$this->_input = InputLoader::LoadAsTextBlob($file);
	}

}