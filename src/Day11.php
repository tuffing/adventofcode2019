<?php 
class Day11 implements iDay
{
	private $_input;


	public function __construct($input) {
		$this->setInput($input);


		$this->_vm_config = [
				[
					'name' => 'PAINT',
					'program' => $this->_input,
					'initial_input' => []
				]
		];
	}

	public function runPart1() {
		$visited = $this->runRobot(0)['visited'];
		return count($visited);
	}

	public function runPart2() {
		$res = $this->runRobot(1);
		//var_dump($res['visited']);
		for ($y = $res['maxY'];  $y >= $res['minY']; $y--) {
			for ($x = $res['minX']; $x<= $res['maxX']; $x++) {
				if (isset($res['visited']["$x,$y"]) && $res['visited']["$x,$y"] == 1) {
					print('█');
				}
				else {
					print(' ');
				}
			}

			print("\n");
		}

		return 'see above';
	}

	public function runRobot($start) {
		$x = 0;
		$y = 0;
		$dir_mod = [[0,1],[1,0], [0,-1], [-1,0]]; //n, e, s, w
		$dir = 0;
		$visited = [];
		$visited["$x,$y"] = $start;

		$processor = new IntCodeProcessor($this->_vm_config);
		$vm = $processor->getVmAt(0);
		$input = $vm->getInputQueue();
		$output = $vm->getOutputQueue();

		$minX = 0;
		$maxX = 0;
		$minY = 0;
		$maxY = 0;

		while($vm->isActive()) {
			$color = 0;
			if (array_key_exists("$x,$y", $visited)) {
				$color = $visited["$x,$y"];

			}

			$input->push($color);

			//get color
			while ($vm->isActive() && count($output) == 0) {
				$vm->step();
			}

			if (!$vm->isActive()) {
				break;
			}

			$visited["$x,$y"] = $output->pop();

			//dir
			while ($vm->isActive() && count($output) == 0) {
				$vm->step();
			}

			if (!$vm->isActive()) {
				break;
			}
			$o = $output->pop();

			$dir = $o < 1 ? $dir - 1 : $dir + 1;
			$dir = $dir == -1 ? 3 : $dir;
			$dir = $dir % 4;

			$x += $dir_mod[$dir][0];
			$y += $dir_mod[$dir][1];

			$minX = min($minX, $x);
			$minY = min($minY, $y);
			$maxX = max($maxX, $x);
			$maxY = max($maxY, $y);
		}

		return ['visited' => $visited, 'minX' => $minX, 'minY' => $minY, 'maxX' => $maxX, 'maxY' => $maxY];
	}

	public function setInput($input) {
		//parse input
		$file = "Day11/".$input;
		//$this->_input = InputLoader::LoadAsArrayOfLines($file);
		$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
		//$this->_input = InputLoader::LoadAsTextBlob($file);
	}

}