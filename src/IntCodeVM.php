<?php
class IntCodeVM {

	private $_name;
	public $_input;
	private $_program;
	private $_index = 0;
	public $_output;
	private $rel_base = 0;
	private $_active = true;
	private $_waiting = false;
	private $_no_wait_mode = false;
	private $_idle = false;
	private	$_cmds = array(
				1 => "add",
				2 => "mult",
				3 => "consumeInput",
				4 => "output",
				5 => "jumpIftrue",
				6 => "jumpIfFalse",
				7 => "lessThan",
				8 => "greaterThan",
				9 => "adjustRelBase",
				99 => "terminate",
				);

	public function __construct($name, $program, $initial = [], $no_wait_mode = false) {
		$this->_name = $name;
		$this->_program = $program;
		$this->_input = new \Ds\Queue($initial);
		$this->_output = new \Ds\Queue();
		$this->_no_wait_mode = $no_wait_mode;
	}

	public function __toString() {
		return $this->_name;
	}

	public function setOutputQueue(&$output_queue) {
		$this->_output = $output_queue;  
	}

	public function getInputQueue() {
		return $this->_input;
	}

	public function getOutputQueue() {
		return $this->_output;
	}

	public function dumpProgram() {
		file_put_contents ('game.txt', implode(',', $this->_program));
	}


	public function setReg($i, $value) {
		$this->_program[$i] = $value;
	}

	public function getReg($i) {
		return $this->_program[$i];
	}

	public function step() {
		if (!$this->_active) {
			return false;
		}

		if ($this->_program[$this->_index] > 10) {
			$op = (int)substr((string)$this->_program[$this->_index], -2);
		}
		else {
			$op = $this->_program[$this->_index];
		}

		$this->{$this->_cmds[$op]}();

		if ($this->_index > count($this->_program)) {
			$this->_active = false;
		}

		return $this->_active;
	}

	public function isActive() {
		return $this->_active;
	}

	public function isWaiting() {
		return $this->_waiting;
	}

	public function isIdle() {
		return $this->_idle > 1;
	}

	public function insertInput($input) {
		$this->_idle = 0;
		$this->_input->push($input);
	}

	private function genParams($count) {
		$op = $this->_program[$this->_index];
		$val = str_pad($op, $count+2, '0', STR_PAD_LEFT);

		$codes = str_split($val);
		$op = array_pop($codes);
		$op = array_pop($codes) + $op;

		$params = [];
		for ($i = 1; $i <= $count; $i++) {
			$p = array_pop($codes);

			if ($p == 0) {
				if (!isset($this->_program[$this->_index+$i])) {
					$this->_program[$this->_index+$i] = 0;
				}
				if (!isset($this->_program[$this->_program[$this->_index+$i]])) {
					$this->_program[$this->_program[$this->_index+$i]] = 0;
				}
				$params[] = ($count >= 3 && $i == $count) || $op == '03' ? $this->_program[$this->_index+$i] : (int)$this->_program[$this->_program[$this->_index+$i]];
			}
			else if ($p == '1') {
				$params[] = (int)$this->_program[$this->_index+$i];
			}
			else {//relative mode
				$rel = $this->rel_base + $this->_program[$this->_index+$i];
				if (!isset($this->_program[$rel])) {
					$this->_program[$rel] = 0;
				}

				$params[] = ($count >= 3 && $i == $count) || $op == '03' ? $rel : (int)$this->_program[$rel];
			}
		}

		return $params;
	}    

	//1
	private function add() {
		$params = $this->genParams(3);
		$this->_program[$params[2]] = isset($this->_program[$params[2]]) ? $this->_program[$params[2]] : 0; 
		$this->_program[$params[2]] = $params[0] + $params[1];
		$this->_index += 4;
	}

	//9
	private function adjustRelBase() {
		/*Opcode 9 adjusts the relative base by the value of its only parameter. The relative base increases (or decreases, if the value is negative) by the value of the parameter.*/
		$params = $this->genParams(1);
		$this->rel_base += $params[0];
		$this->_index += 2;
	}

	//2
	private function mult() {
		$params = $this->genParams(3);
		$this->_program[$params[2]] = isset($this->_program[$params[2]]) ? $this->_program[$params[2]] : 0; 
		$this->_program[$params[2]] = $params[0] * $params[1];
		$this->_index += 4;
	}

	//3
	private function consumeInput() {
		if (!$this->_no_wait_mode && $this->_input->isEmpty()) {
			$this->_waiting = true;
			$this->_idle++;
			//do nothing
			return;
		}
		else if ($this->_no_wait_mode && $this->_input->isEmpty()) {
			$this->_input->push(-1);
			$this->_idle++;

		}
		else {
			$this->_idle = 0;
		}
		$params = $this->genParams(1);

		$this->_program[$params[0]] = $this->_input->pop();
		$this->_index += 2;
		$this->_waiting = false;
	}

	//4
	private function output() {
		$this->_idle = 0;
		$params = $this->genParams(1);
		$this->_output->push($params[0]);

		$this->_index += 2;
	}

	//5
	private function jumpIftrue() {
		// Opcode 5 is jump-if-true: if the first parameter is non-zero, it sets the instruction pointer to the value from the second parameter. Otherwise, it does nothing.
		$params = $this->genParams(2);

		if ($params[0] != 0) {
			$this->_index = $params[1];
		}
		else {
			$this->_index += 3;
		}
	}

	//6
	private function jumpIfFalse() {
		//Opcode 6 is jump-if-false: if the first parameter is zero, it sets the instruction pointer to the value from the second parameter. Otherwise, it does nothing.
		$params = $this->genParams(2);

		if ($params[0] == 0) {
			$this->_index = $params[1];
		}
		else {
			$this->_index += 3;
		} 
	}

	//7
	private function lessThan() {
		//Opcode 7 is less than: if the first parameter is less than the second parameter, it stores 1 in the position given by the third parameter. Otherwise, it stores 0.
		$params = $this->genParams(3);

		$this->_program[$params[2]] = isset($this->_program[$params[2]]) ? $this->_program[$params[2]] : 0; 
		$this->_program[$params[2]] = $params[0] < $params[1] ? 1 : 0;
		
		$this->_index += 4;
	}

	//8
	private function greaterThan() {
		//Opcode 8 is equals: if the first parameter is equal to the second parameter, it stores 1 in the position given by the third parameter. Otherwise, it stores 0.
		$params = $this->genParams(3);

		$this->_program[$params[2]] = isset($this->_program[$params[2]]) ? $this->_program[$params[2]] : 0; 
		$this->_program[$params[2]] = $params[0] === $params[1] ? 1 : 0;
		
		$this->_index += 4;
	}

	//99
	private function terminate() {
		$this->_index = count($this->_program) + 1;
		$this->_active = false;
	}

}


class IntCodeProcessor {
	private $_VMs = [];

	/**
	 * 
	 *  @param string $vm_config as follows:
	 * 		[
	 *			[
	 *				'name' => string
	 *				'program' => [array of ints],
	 *				[optional] 'output_to_vm' => index of program to output to,
	 *				'initial_input' => [arry of ints]
	 *			],
	 *			...vm2..vm3 etc
	 *		] 
	 */
	public function __construct($vm_config = []) {
		foreach ($vm_config as $config) {
			$no_wait = false;
			if (isset($config['no_wait'])) {
				$no_wait = true;
			}
			$vm = new IntCodeVM($config['name'], $config['program'], $config['initial_input'], $no_wait);
			$this->_VMs[] = $vm;
		}
		//set the output queues
		foreach ($vm_config as $key => $config) {
			if (isset($config['output_to_vm'])) {
				$target = $this->_VMs[$config['output_to_vm']]->getInputQueue();
				$this->_VMs[$key]->setOutputQueue($target);
			}
		}
	}

	public function getVmAt($i) {
		return $this->_VMs[$i];
	}

	public function run($vm_index_for_output) {
		while (true) {
			$active_count = 0;
			foreach ($this->_VMs as $vm) {
				if ($vm->step()) {
					$active_count++;
				}
			}

			if ($active_count < 1) {
				break;
			}

		}

		return $this->_VMs[$vm_index_for_output]->getOutputQueue()->pop();
	}

	public function run_network_mode_with_nat() {
		$done = false;
		$p1 = 0;
		$Nx = 0;
		$Ny = 0;
		$prevY = -1;
		while (!$done) {
			$active_count = 0;
			$idle_count = 0;
			foreach ($this->_VMs as $id => $vm) {
				$vm->step();

				$out = $vm->getOutputQueue();
				if (count($out) == 3) {
					$key = $out->pop();
					$x = $out->pop();
					$y = $out->pop();
					if ($key < 50) {
						$this->_VMs[$key]->insertInput($x);
						$this->_VMs[$key]->insertInput($y);
					}
					else if ($key == 255) {
						if ($p1 == 0) {
							$p1 = $Ny;
						}
						$Nx = $x;
						$Ny = $y;
					}
				}

				if ($vm->isIdle()) {
					$idle_count++;
				}

				if (!$vm->isActive()) {
					$done = false;
					break;
				}
			}

			if ($idle_count == count($this->_VMs)) {
				$this->_VMs[0]->insertInput($Nx);
				$this->_VMs[0]->insertInput($Ny);

				if ($prevY == $Ny) {
					return [$p1, $Ny];
				}

				$prevY = $Ny;
			}
		}
	}

}