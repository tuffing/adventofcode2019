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

		for ($x = 0; $x < 50; $x++) {
			for ($y=0; $y < 50; $y++) { 
				$coords->push($x);
				$coords->push($y);
			}
		}

		//1731 //2083

		$count = 0;
		$check = 0;
		$show = false;
		$firstX = -1;
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

				$count += $o;
				$check++;

				if ($o == 1 && $firstX == -1) {
					$firstX = $check;
				}

			}
		}

		return $count;
	}

	public function runPart2() {
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



	public function setInput($input) {
		//parse input
		$file = "Day19/".$input;
		//$this->_input = InputLoader::LoadAsArrayOfLines($file);
		$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
		//$this->_input = InputLoader::LoadAsTextBlob($file);
	}

}