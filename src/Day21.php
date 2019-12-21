<?php 
class Day21 implements iDay
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
		/*
			A = 1, B = 2, C = 3, D = 4

			J = jump
			T = temp

		    AND X Y sets Y to true if both X and Y are true; otherwise, it sets Y to false.
		    OR X Y sets Y to true if at least one of X or Y is true; otherwise, it sets Y to false.
		    NOT X Y sets Y to true if X is false; otherwise, it sets Y to false.

		    PATH

		    # ###############  # ###########   #####
		*/

		$ints = [
			'NOT B T',
			'NOT C J',
			'OR T J',
			'AND D J',
			'NOT A T',
			'OR T J',
			'WALK'
		];


		return $this->runRobot($ints);
	}

	public function runRobot($instructions) {
		$processor = new IntCodeProcessor($this->_vm_config);
		$vm = $processor->getVmAt(0);
		$input = $vm->getInputQueue();
		$output = $vm->getOutputQueue();

		foreach ($instructions as $int) {
			$input->push(...$this->genIntInput($int));
		}

		$show = true;
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
	}

	public function runPart2() {
		/*
			A = 1, B = 2, C = 3, D = 4, E = 5, F= 6, G = 7, H = 8, I = 9

			J = jump
			T = temp

		    AND X Y sets Y to true if both X and Y are true; otherwise, it sets Y to false.
		    OR X Y sets Y to true if at least one of X or Y is true; otherwise, it sets Y to false.
		    NOT X Y sets Y to true if X is false; otherwise, it sets Y to false.

		    PATH:

		    # ###############  # ###########   ##########.#.#...#.######...###..####
		*/

		$ints = [
			'NOT B T',
			'NOT C J',
			'OR T J',
			'AND D J',
			'NOT A T',
			'OR T J',
			'OR J T',
			'AND H T',
			'OR E T',
			'AND T J',
			'RUN'
		];


		return $this->runRobot($ints);
	}

	public function genIntInput($command) {
		$ascii = array_map(function ($a) { return ord($a); }, str_split($command));
		$ascii[] = 10;
		return $ascii;
	}

	public function setInput($input) {
		//parse input
		$file = "Day21/".$input;
		$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
	}

}