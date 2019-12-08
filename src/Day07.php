<?php 
class Day07 implements iDay
{
	private $_input;
	private	$_vm_config;


	public function __construct($input) {
		$this->setInput($input);

		$this->_vm_config = [
			[
				'name' => 'A',
				'program' => $this->_input,
				'output_to_vm' => 1,
			],
			[
				'name' => 'B',
				'program' => $this->_input,
				'output_to_vm' => 2,
			],
			[
				'name' => 'C',
				'program' => $this->_input,
				'output_to_vm' => 3,
			],
			[
				'name' => 'D',
				'program' => $this->_input,
				'output_to_vm' => 4,
			],
			[
				'name' => 'E',
				'program' => $this->_input,
			]
		];
	}

	public function runPart1() {
		return $this->findBest([0,1,2,3,4]);
	}


	public function runPart2() {
		$this->_vm_config[4]['output_to_vm'] = 0;

		return $this->findBest([5,6,7,8,9]);
	}

	public function findBest($range = [0,1,2,3,4]) {
		$best = 0;

		foreach (new PermutationIterator($range) as $perm) {
			$this->_vm_config[0]['initial_input'] = [$perm[0], 0];
			$this->_vm_config[1]['initial_input'] = [$perm[1]];
			$this->_vm_config[2]['initial_input'] = [$perm[2]];
			$this->_vm_config[3]['initial_input'] = [$perm[3]];
			$this->_vm_config[4]['initial_input'] = [$perm[4]];

			$processor = new IntCodeProcessor($this->_vm_config);
			$best = max($processor->run(4), $best);
		}
		return $best;
	}

	public function setInput($input) {
		$file = "Day07/".$input;
		//$file = "Day07/test1.txt";
		$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
	}
}


