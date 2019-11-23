<?php 
class Day01 implements iDay
{
	private $_input;

	public function __construct($input) {
		$this->setInput($input);
	}

	public function runPart1() {
		return 1;
	}

	public function runPart2() {
		return 2;
	}

    public function setInput($input) {
    	//parse input
    	$file = "Day01/".$input;
    	$_input = $input;
    }

}