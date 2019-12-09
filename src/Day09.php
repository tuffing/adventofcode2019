<?php 
class Day09 implements iDay
{
	private $_input;
	private	$_vm_config;


	public function __construct($input) {
		$this->setInput($input);

		$this->_vm_config = [
			[
				'name' => 'A',
				'program' => $this->_input,
				'initial_input' => [1]
			]
		];
	}

	public function runPart1() {
		$processor = new IntCodeProcessor($this->_vm_config);
		return $processor->run(0);
	}


	public function runPart2() {
		$this->_vm_config[0]['initial_input'] = [2];
		
		$processor = new IntCodeProcessor($this->_vm_config);
		return $processor->run(0);
	}


	public function setInput($input) {
		$file = "Day09/".$input;
		//$file = "Day09/test1.txt";
		$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
	}
}


