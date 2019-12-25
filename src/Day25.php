<?php 
class Day25 implements iDay
{
	private $_input;


	public function __construct($input) {
		$this->setInput($input);


		$this->_vm_config = [
				[
					'name' => 'Screen',
					'program' => $this->_input,
					'initial_input' => []
				]
		];
	}

	public function runPart1() {
		$this->playGame();
		return 0;
	}

	public function runPart2() {
		return 0;
	}

	public function playGame($autoplay = true) {
		print("Just play the game, it's easy enough \n");
		$visited = [];
		$input = $this->_input;

		$vm_config = [
				[
					'name' => 'Screen',
					'program' => $input,
					'initial_input' => []
				]
		];
		$processor = new IntCodeProcessor($vm_config);
		$vm = $processor->getVmAt(0);
		$input = $vm->getInputQueue();
		$output = $vm->getOutputQueue();


		while($vm->isActive()) {
			//get color
			while ($vm->isActive() && count($output) == 0 && (!$vm->isWaiting() || count($input))) {
				$vm->step();
			}

			if (!$vm->isActive()) {
				break;
			}

			if ($vm->isWaiting()) {
				$command = readline("\nAccepted north, south, east, or west | take <some item> | drop <some item> | inv (list items)? ");
				$ascii = $this->genIntInput($command);
				$input->push(...$ascii);
				continue;
			}


			$o = $output->pop();

			if ($o >= 10 && $o <= 255) {
				print(chr($o));
			}
			else {
				print("$o ????? \n");
			}
		}

		return 0;
	}

	public function genIntInput($command) {
		$ascii = array_map(function ($a) { return ord($a); }, str_split($command));
		$ascii[] = 10;
		return $ascii;
	}

	public function setInput($input) {
		//parse input
		$file = "Day25/".$input;
		//$this->_input = InputLoader::LoadAsArrayOfLines($file);
		$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
		//$this->_input = InputLoader::LoadAsTextBlob($file);
	}

}