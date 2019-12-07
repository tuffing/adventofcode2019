<?php 
class Day07 implements iDay
{
	private $_input;

	public function __construct($input) {
		$this->setInput($input);
	}

	public function runPart1() {
		$nums = [0,1,2,3,4];
		$this->heapPermutation($nums, 5,5, $perms, $count);
		$best = 0;

		foreach ($perms as $perm) {
			$output = 0;

			for ($i = 0; $i < 5; $i++) {
				$output = $this->runProgram($this->_input, [$perm[$i], $output]);
			}

			if ($output > $best) {
				$best = $output;
			}
		}
		return $best;
	}


	public function heapPermutation(&$a, $size, $n, &$perms, &$count) 
    { 
        if ($size == 1) {
        	$a[] = $n;
        	$count++;
            $perms[] = $a; 
        }
   
        for ($i=0; $i<$size; $i++) 
        { 
            $this->heapPermutation($a, $size-1, $n, $perms, $count); 
   
            if ($size % 2 == 1)  { 
                $temp = $a[0]; 
                $a[0] = $a[$size-1]; 
                $a[$size-1] = $temp; 
            } 
            else { 
                $temp = $a[$i]; 
                $a[$i] = $a[$size-1]; 
                $a[$size-1] = $temp; 
            } 
        } 
    } 

	public function runPart2() {
		$nums = [5,6,7,8,9];
		$this->heapPermutation($nums, 5,5, $perms, $count);
		$best = 0;

		foreach ($perms as $perm) {
			$input1 = $this->_input;
			$input2 = $this->_input;
			$input3 = $this->_input;
			$input4 = $this->_input;
			$input5 = $this->_input;

			$i_1 = 0;
			$i_2 = 0;
			$i_3 = 0;
			$i_4 = 0;
			$i_5 = 0;
			$cmd_input1 = [$perm[0],0];
			$cmd_input2 = [$perm[1]];
			$cmd_input3 = [$perm[2]];
			$cmd_input4 = [$perm[3]];
			$cmd_input5 = [$perm[4]];
			$output = 0;

			while ($i_1 < count($input1) || $i_2 < count($input2) || $i_3 < count($input3) || $i_4 < count($input4) || $i_5 < count($input5)) {
					$input1= $this->step($i_1, $input1, $cmd_input1, $cmd_input2, $output);
					$input2= $this->step($i_2, $input2, $cmd_input2, $cmd_input3, $output);
					$input3= $this->step($i_3, $input3, $cmd_input3, $cmd_input4, $output);
					$input4= $this->step($i_4, $input4, $cmd_input4, $cmd_input5, $output);
					$input5= $this->step($i_5, $input5, $cmd_input5, $cmd_input1, $output);
			}

			if (count($cmd_input1) > 0 && $cmd_input1[count($cmd_input1) - 1] > $best) {
				$best = $cmd_input1[count($cmd_input1) - 1];
			}
		}
		return $best;
	}

	private function step(&$i, &$input, &$cmp_input, &$cmp_input_friend, &$output) {
		if ($i > count($input)) {
			return $input;
		}

		if ($input[$i] > 10) {
			$op = (int)substr((string)$input[$i], -2);
		}
		else {
			$op = $input[$i];
		}

		//ops
		if ($op == 99) {
			$i = count($input) + 1;
			return $input;
		}
		else if ($op === 1) {
			$params = $this->genParams($input[$i], 3, $i, $input);
			$input[$input[$i+3]] = $params[0] + $params[1];
			$i += 4;
		}
		else if ($op === 2) {
			$params = $this->genParams($input[$i], 3, $i, $input);
			$input[$input[$i+3]] = $params[0] * $params[1];
			$i += 4;
		}
		else if ($op === 3) { //input
			//take input. On this exercise it's always the same per command
			if (!count($cmp_input)) {
				//do nothing
				return $input;
			}
			$inp = array_shift($cmp_input);
			$input[$input[$i+1]] = $inp;
			$i += 2;
		}
		else if ($op === 4) { //output mode
			$params = $this->genParams($input[$i], 1, $i, $input);

			$output = $params[0];
			$cmp_input_friend[] = $output;

			$i += 2;
		}
		else if ($op === 5) {
			// Opcode 5 is jump-if-true: if the first parameter is non-zero, it sets the instruction pointer to the value from the second parameter. Otherwise, it does nothing.
			$params = $this->genParams($input[$i], 2, $i, $input);

			if ($params[0] != 0) {
				$i = $params[1];
			}
			else {
				$i += 3;
			}
			
		}
		else if ($op === 6) {
	    	//Opcode 6 is jump-if-false: if the first parameter is zero, it sets the instruction pointer to the value from the second parameter. Otherwise, it does nothing.
			$params = $this->genParams($input[$i], 2, $i, $input);

			if ($params[0] == 0) {
				$i = $params[1];
			}
			else {
				$i += 3;
			}    	
			
		}
		else if ($op === 7) {
	    	//Opcode 7 is less than: if the first parameter is less than the second parameter, it stores 1 in the position given by the third parameter. Otherwise, it stores 0.
			$params = $this->genParams($input[$i], 3, $i, $input);

			$input[$input[$i+3]] = $params[0] < $params[1] ? 1 : 0;
			
			$i += 4;
		}
		else if ($op === 8) {
	    	//Opcode 8 is equals: if the first parameter is equal to the second parameter, it stores 1 in the position given by the third parameter. Otherwise, it stores 0.
			$params = $this->genParams($input[$i], 3, $i, $input);

			$input[$input[$i+3]] = $params[0] === $params[1] ? 1 : 0;
			
			$i += 4;
			
		}
		else {
			exit("unknown command ". $input[$i]);
		}	

		return $input;
	}


	private function runProgram($input, $cmp_input) {
		$i = 0;
		$friend = [];
		while ($i < count($input)) {
			$input = $this->step($i, $input, $cmp_input, $friend, $output);

		}

		return $output;
	}

	private function genParams($op, $count, $index, $input) {
		$val = str_pad($op, $count+2, '0', STR_PAD_LEFT);

		$codes = str_split($val);
		array_pop($codes);
		array_pop($codes);

		$params = [];
		for ($i = 1; $i <= $count; $i++) {
			$p = array_pop($codes);
			if ($p == 0) {
				$params[] = (int)$input[$input[$index+$i]];
			}
			else {
				$params[] = (int)$input[$index+$i];
			}
		}


		return $params;
	}

	public function setInput($input) {
		$file = "Day07/".$input;
		//$file = "Day07/test1.txt";
		$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
	}
}


