<?php 
class PermutationIterator implements Iterator {
   
    private $position;
    private $perms = [];

    public function __construct($arr) {
        $this->position = 0;
        $this->heapPermutation($arr, count($arr), count($arr));
    }
   
  	private function heapPermutation(&$a, $size, $n) 
	{ 
		if ($size == 1) {
			$a[] = $n;
			$this->perms[] = $a; 
		}
		for ($i=0; $i<$size; $i++) 
		{ 
			$this->heapPermutation($a, $size-1, $n); 

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

    function rewind() {
        $this->position = 0;
    }

    function current() {
        return $this->perms[$this->position];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->perms[$this->position]);
    }
   
}