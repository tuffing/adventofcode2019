<?php 
class Day19 implements iDay
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
		$processor = new IntCodeProcessor($this->_vm_config);
		$vm = $processor->getVmAt(0);
		$coords = new \Ds\Queue();;
		$output = $vm->getOutputQueue();

		$minX = 0;
		$maxX = 50;

		$minY = 0;
		$maxY = 50;


		$wX = $maxX-$minX;
		$wY = $maxY-$minY;

		for ($y = $minY; $y < $maxY; $y++) {
			for ($x=$minX; $x < $maxX; $x++) { 
				$coords->push($x);
				$coords->push($y);
			}
		}

		//1731 //2083

		$count = 0;
		$check = 0;
		$show = false;
		$draw = false;
		while (count($coords)) {
			$processor = new IntCodeProcessor($this->_vm_config);
			$vm = $processor->getVmAt(0);
			$input = $vm->getInputQueue();
			$output = $vm->getOutputQueue();
			$input->push($coords->pop());
			$input->push($coords->pop());

			while($vm->isActive()) {
				while ($vm->isActive() && count($output) == 0 && (!$vm->isWaiting() || count($input))) {
					$vm->step();
				}

				if (!$vm->isActive()) {
					break;
				}

				if ($vm->isWaiting()) {
					return $count;
				}


				$o = $output->pop();

				if ($draw && $o == 0)
					print(".");
				else if ($draw) 
					print("#");

				$count += $o;
				$check++;

				if ($draw && $check % $wX == 0)
					print("\n");

			}
		}

		return $count;
	}

	public function runPart2() {
		//did by hand. 
		//@todo do this programitically. 
		//could binary search it. go along one axis until i can go 100 back. 
		//then go down 100. doesnt work? try again higher number etc. then come back again
		return (1509 * 10000) + 773;
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



	public function setInput($input) {
		//parse input
		$file = "Day19/".$input;
		//$this->_input = InputLoader::LoadAsArrayOfLines($file);
		$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
		//$this->_input = InputLoader::LoadAsTextBlob($file);
	}

}