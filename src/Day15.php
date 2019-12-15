<?php 
class Day15 implements iDay
{
	private $_input;
	private $_res;

	public function __construct($input) {
		$this->setInput($input);


		$this->_vm_config = [
				[
					'name' => 'Find',
					'program' => $this->_input,
					'initial_input' => []
				]
		];
	}

	public function runPart1() {
		$res = $this->runRobot(0);
		$repX = $res['repX'];
		$repY = $res['repY'];

		$this->_res = $res;
		return $res["visited"]["$repX,$repY"];
	}

	public function print($res) {
		for ($y = $res['maxY'];  $y >= $res['minY']; $y--) {
			for ($x = $res['minX']; $x<= $res['maxX']; $x++) {
				if ($x == 0 &&  $y == 0) {
						print('S');
				}
				else if ($x == 16 && $y == 16) {
					print('T');
				}
				if (isset($res['visited']["$x,$y"]) && $res['visited']["$x,$y"] < 0) {
					print('█,');
				}
				else if (isset($res['visited']["$x,$y"])) {
					print($res['visited']["$x,$y"] . ",");
				}
				else {
					print('.,');
				}
			}

			print("\n");
		}

	}

	public function runPart2() {
		$res = $this->_res;
		$map = $res['visited'];
		$sX = $res['repX'];
		$sY = $res['repY'];
		$next = [[$sX,$sY]];
		$count = 0;
		$dir_mod = [1 => [0,-1], 2 => [0,1], 3 => [-1,0], 4 => [1,0]]; //n,s,w,e


		while (count($next)) {
			$new = [];

			foreach ($next as $value) {
				for ($i = 1; $i <= 4; $i++) {
					$nextX = $value[0] + $dir_mod[$i][0];
					$nextY = $value[1] + $dir_mod[$i][1];

					if (isset($map["$nextX,$nextY"]) && $map["$nextX,$nextY"] != 'O' && $map["$nextX,$nextY"] >= 0) {
						$map["$nextX,$nextY"] = 'O';
						$new[] = [$nextX, $nextY];
					}
				}
			}

			$next = $new;

			if (count($new)) {
				$count++;
			}
		}


		return $count;

	}

	public function runRobot() {
		$x = 0;
		$y = 0;		
		$dir_mod = [1 => [0,-1], 2 => [0,1], 3 => [-1,0], 4 => [1,0]]; //n,s,w,e
		$next_dir = 1;
		$nextX = $x + $dir_mod[$next_dir][0];
		$nextY = $y + $dir_mod[$next_dir][1];

		$visited = [];
		$visited["$x,$y"] = 0;
		$steps = 0;

		$processor = new IntCodeProcessor($this->_vm_config);
		$vm = $processor->getVmAt(0);
		$input = $vm->getInputQueue();
		$output = $vm->getOutputQueue();

		//mapping purposes
		$minX = 0;
		$maxX = 0;
		$minY = 0;
		$maxY = 0;

		//out answer
		$repX = 0;
		$repY = 0;

		//stack to retrace steps
		$path  = new \Ds\Stack();

		while($vm->isActive()) {
			while ($vm->isActive() && count($output) == 0 && (!$vm->isWaiting() || count($input))) {
				$vm->step();
			}

			if (!$vm->isActive()) {
				break;
			}

			if ($vm->isWaiting()) {
				if ($next_dir == 0) {
					die("NOT SET");
				}
				$input->push($next_dir);
				continue;
			}

			//output..
			/*
			0: The repair droid hit a wall. Its position has not changed.
			1: The repair droid has moved one step in the requested direction.
			2: The repair droid has moved one step in the requested direction; its new position is the location of the oxygen system.
			*/
			$o = $output->pop();

			if ($o == 2) {
				$repX = $nextX;
				$repY = $nextY;
			}

			if ($o == 0) {
				$visited["$nextX,$nextY"] = -1;

			}

			if (in_array($o, [1,2])) {
				//print("hello?");
				if (!isset($visited["$nextX,$nextY"]) || $visited["$nextX,$nextY"] > $visited["$x,$y"] + 1) {
					$visited["$nextX,$nextY"] = $visited["$x,$y"] + 1;
					$path->push([$x,$y]);
				}
				$x = $nextX;
				$y = $nextY;
			}
			/*Accept a movement command via an input instruction.
			Send the movement command to the repair droid.
			Wait for the repair droid to finish the movement operation.
			Report on the status of the repair droid via an output instruction.*/

			/*north (1), south (2), west (3), and east (4)*/
			/*$n = $this->coordsDirHash($x, $y, 1);
			$s = $this->coordsDirHash($x, $y, 2);
			$w = $this->coordsDirHash($x, $y, 3);
			$e = $this->coordsDirHash($x, $y, 4);*/
			for ($i = 1; $i <= 5; $i++) {
				if ($i == 5) {
					if (count($path) == 0) {
						//no where else to go, so back track
						return ['visited' => $visited, 'repX' => $repX, 'repY' => $repY, 'minX' => $minX, 'minY' => $minY, 'maxX' => $maxX, 'maxY' => $maxY];
					}

					$next = $path->pop();
					$nextX = $next[0];
					$nextY = $next[1];

					if ($nextX < $x) {
						//west
						$next_dir = 3;
					}
					else if ($nextX > $x) {
						$next_dir = 4;
					}
					else if ($nextY < $y) {
						$next_dir = 1;
					}
					else {
						$next_dir = 2;
					}

					break;
				}
				$coord = $this->coordsDirHash($x, $y, $dir_mod, $i);
				//if we haven't been there before. or steps to get there are worse. go there next
				if (!array_key_exists($coord, $visited) || $visited[$coord] > $visited["$x,$y"]+1 ) {
					$next_dir = $i;
					$nextX = $x + $dir_mod[$i][0];
					$nextY = $y + $dir_mod[$i][1];

					break;
				}
			}
			$minX = min($minX, $x);
			$minY = min($minY, $y);
			$maxX = max($maxX, $x);
			$maxY = max($maxY, $y);
		}

		return ['visited' => $visited, 'repX' => $repX, 'repY' => $repY, 'minX' => $minX, 'minY' => $minY, 'maxX' => $maxX, 'maxY' => $maxY];
	}

	public function coordsDirHash($x, $y, $dir_mod, $dir) {
		$x += $dir_mod[$dir][0];
		$y += $dir_mod[$dir][1];

		return "$x,$y";
	}

	public function setInput($input) {
		//parse input
		$file = "Day15/".$input;
		//$this->_input = InputLoader::LoadAsArrayOfLines($file);
		$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
		//$this->_input = InputLoader::LoadAsTextBlob($file);
	}

}