<?php 
class Day13 implements iDay
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
		return 0;
		$processor = new IntCodeProcessor($this->_vm_config);
		$vm = $processor->getVmAt(0);
		$input = $vm->getInputQueue();
		$output = $vm->getOutputQueue();

		$count = 0;

		$blocks = 0;

		while($vm->isActive()) {
			//get color
			while ($vm->isActive() && count($output) == 0) {
				$vm->step();
			}

			if (!$vm->isActive()) {
				break;
			}

			//$visited["$x,$y"] = $output->pop();
			if ($count % 3 == 2) {
				$tile = $output->pop();

				if ($tile == 2) {
					$blocks++;
				}
			}
			else {
				$output->pop();
			}

			$count++;
		}

		return $blocks;
	}

	public function runPart2() {
		return $this->playGame();
		$res = $this->runRobot(1);
		//var_dump($visited);
		for ($y = $res['maxY'];  $y >= $res['minY']; $y--) {
			for ($x = $res['minX']; $x<= $res['maxX']; $x++) {
				if (isset($visited["$x,$y"]) && $visited["$x,$y"] == 1) {
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

	public function playGame($autoplay = true) {
		$visited = [];
		$input = $this->_input;
		$input[0] = 2;

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

		$count = 0;
		$score = 0;
		$command = 0;
		$cheated = false;

		$ball = '';
		$last = 50;
		while($vm->isActive()) {
			//get color
			while ($vm->isActive() && count($output) == 0 && (!$vm->isWaiting() || count($input))) {
				$vm->step();
			}

			if (!$vm->isActive()) {
				break;
			}

			if ($vm->isWaiting()) {
				if ($autoplay) {
					//auto play cheats straight away
					if (!$cheated) {
						$this->layerBottom($vm);
						$cheated = true;
					}
					$input->push($command);
					continue;
				}

				$this->draw($visited);


				$command = (int)readline("Command -1 0 1 (or 2 to put ball back at start, 3 to auto move to the ball, 4 to lay the entire ground with points., 5 to let the game play by itself): ");

				print("Score: $score");

				if ($command == 2) {
					$vm->setReg(388, 20);
					$vm->setReg(389, 20);
					$command = (int)readline("you just reset. new command  -1 0 1 ");
				}
				else if ($command == 3) {
					$vm->setReg(392, $vm->getReg(388));
					$command = (int)readline("you just cheatd. new command  -1 0 1 ");

				}
				else if ($command == 4 || $command == 5) {
					$this->layerBottom($vm);

					if ($command == 4)
						$command = (int)readline("you just cheatd. new command  -1 0 1 ");
					else  {
						$autoplay = true;
						$command = 0;
					}

				}

				$input->push($command);
				continue;
			}

			if ($count % 3 == 0) {
				$x = $output->pop();
			}
			else if ($count % 3 == 1) {
				$y = $output->pop();
			}
			else {
				if ($x == -1 && $y == 0) {
					//print('hi');
					$score = $output->pop();
				}
				else {
					$tile = $output->pop();
					$visited["$x,$y"] = $tile;
				}
			}

			$count++;
		}

		return $score;
	}

	private function layerBottom($vm) {
		for ($a = 1520; $a < 1559; $a++) {
			$vm->setReg($a, 3);
		}
	}

	public function draw($visited) {
		for ($y = 0;  $y < 30; $y++) {
			for ($x = 0; $x<= 41; $x++) {
				if (!isset($visited["$x,$y"])) {
					continue;
				}

				/*0 is an empty tile. No game object appears in this tile.
				1 is a wall tile. Walls are indestructible barriers.
				2 is a block tile. Blocks can be broken by the ball.
				3 is a horizontal paddle tile. The paddle is indestructible.
				4 is a ball tile. The ball moves diagonally and bounces off objects.*/

				if ($visited["$x,$y"] == 0) {
					print(' ');
				}//█
				if ($visited["$x,$y"] == 1) {
					print('#');
				}
				if ($visited["$x,$y"] == 2) {
					print('█');
				}
				if ($visited["$x,$y"] == 3) {
					print('_');
				}
				if ($visited["$x,$y"] == 4) {
					print('0');
				}
			}

			print("\n");
		}
	}

	public function setInput($input) {
		//parse input
		$file = "Day13/".$input;
		//$this->_input = InputLoader::LoadAsArrayOfLines($file);
		$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
		//$this->_input = InputLoader::LoadAsTextBlob($file);
	}

}