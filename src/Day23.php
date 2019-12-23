<?php 
class Day23 implements iDay
{
	private $_input;
	private	$_vm_config;

	private $_p2 = 0;

	public function __construct($input) {
		$this->setInput($input);

		$this->_vm_config = [];

		for ($i=0; $i < 50; $i++) { 
			# code...
			$this->_vm_config[] = [
				'name' => "$i",
				'program' => $this->_input,
				'initial_input' => [$i],
				'no_wait' => true
			];
		}
	}

	public function runPart1() {
		$processor = new IntCodeProcessor($this->_vm_config);
		$answer = $processor->run_network_mode_with_nat();
		$this->_p2 = $answer[1];

		return $answer[0];
	}


	public function runPart2() {
		return $this->_p2;
	}


	public function setInput($input) {
		$file = "Day23/".$input;
		//$file = "Day07/test1.txt";
		$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
	}
}


