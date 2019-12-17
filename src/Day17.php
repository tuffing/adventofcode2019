<?php 
class Day17 implements iDay
{
	private $_input;
	private $_res;

	public function __construct($input) {
		$this->setInput($input);


		$this->_vm_config = [
				[
					'name' => 'Map',
					'program' => $this->_input,
					'initial_input' => []
				]
		];
	}

	public function runPart1() {
		$map = $this->runMap();
		$sum = 0;

		foreach ($map as $y => $row) {
			foreach($row as $x => $cell) {
				if ($cell == '#') {
					$coords = [[$x,$y-1], [$x,$y+1], [$x-1,$y], [$x+1,$y]];
				}
				else {
					continue;
				}

				$t = 0;
				foreach ($coords as $coord) {
					if (isset($map[$coord[1]]) && isset($map[$coord[1]][$coord[0]])) {
						if ($map[$coord[1]][$coord[0]] == '#') {
							$t ++;
						}
					}
				}

				if ($t == 4) {
					$sum += ($x * $y);
				}
			}

			//print(implode('',$row). "\n");
		}

		
		return $sum;
	}

	public function runPart2() {
		$input = $this->_input;
		$input[0] = 2;

		$vm_config = [[
					'name' => 'Map',
					'program' => $input,
					'initial_input' => []
				]];

		$processor = new IntCodeProcessor($vm_config);
		$vm = $processor->getVmAt(0);
		$input = $vm->getInputQueue();
		$output = $vm->getOutputQueue();

		$input->push(65,44,66,44,65,44,67,44,66,44,67,44,66,44,65,44,67,44,66,10); //A,B,A,C,B,C,B,A,C,B\n
		$input->push(76,44,54,44,82,44,56,44,82,44,49,50,44,76,44,54,44,76,44,56,10); // A = L,6,R,8,R,12,L,6,L,8
		$input->push(76,44,49,48,44,76,44,56,44,82,44,49,50,10); // B = L,10,L,8,R,12  \n 
		$input->push(76,44,56,44,76,44,49,48,44,76,44,54,44,76,44,54,10); //C = L,8,L,10,L,6,L,6 \n


		$input->push(156, 10); //Y 171 = N = 156

		$show = false;
		while($vm->isActive()) {
			while ($vm->isActive() && count($output) == 0 && (!$vm->isWaiting() || count($input))) {
				$vm->step();
			}

			if (!$vm->isActive()) {
				break;
			}

			if ($vm->isWaiting()) {
				die("Waiting??\n");
			}


			$o = $output->pop();

			if ($show && $o >= 10 && $o <= 255) {
				print(chr($o));
			} else if ($o > 1000){
				return $o;
			}	

		}

		return 0;
		//return $map;
	}

	public function runMap() {
		$x = 0;
		$y = 0;		

		$processor = new IntCodeProcessor($this->_vm_config);
		$vm = $processor->getVmAt(0);
		$input = $vm->getInputQueue();
		$output = $vm->getOutputQueue();

		$map = [[]];


		while($vm->isActive()) {
			while ($vm->isActive() && count($output) == 0 && (!$vm->isWaiting() || count($input))) {
				$vm->step();
			}

			if (!$vm->isActive()) {
				break;
			}

			if ($vm->isWaiting()) {
				die("Waiting??\n");
			}


			$o = $output->pop();
			$char = chr($o);
			if ($o == 10) { //new line
				$map[] = [];
			}
			else {
				$map[count($map) - 1][] = $char;
			}

			
		}

		return $map;
	}

	public function coordsDirHash($x, $y, $dir_mod, $dir) {
		$x += $dir_mod[$dir][0];
		$y += $dir_mod[$dir][1];

		return "$x,$y";
	}

	public function setInput($input) {
		//parse input
		$file = "Day17/".$input;
		//$this->_input = InputLoader::LoadAsArrayOfLines($file);
		$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
		//$this->_input = InputLoader::LoadAsTextBlob($file);
	}

}