<?php 
class Day06 implements iDay
{
	public $_input;
	private $orbits;
	private $com_node;

	public function __construct($input) {
		$this->setInput($input);
	}

	public function runPart1() {
		$orbits = $this->parseOrbits();
		return $this->countOrbits($orbits['COM'], 0);
	}

	private function countOrbits($node, $current_count) {
		$total = $current_count;
		if (count($node->children)) {
			$current_count++;
			foreach ($node->children as $child) {
				$total += $this->countOrbits($child, $current_count);
			}
		}

		return $total;
	}

	public function runPart2() {
		//standard equal distance BFS will work here
		$end = 'SAN';
		$steps = ['YOU' => 0];

		$queue = new \Ds\Queue();
		$queue->push($this->orbits['YOU']);

		while (count($queue) > 0) {
			$next = $queue->pop();
			if ($next->name == 'SAN') {
				return $steps['SAN'] - 2;
			}
			//for simplicty sake just through orbits in there
			if ($next->orbits) {
				$next->children[] = $next->orbits;
			}
			foreach ($next->children as $value) {
				if (!array_key_exists($value->name, $steps)) {
					$steps[$value->name] = $steps[$next->name] + 1;
					$queue->push($this->orbits[$value->name]);
				}
			}
		}

	}

	public function parseOrbits() {
		$orbit_list = [];
		$orbits = [];
		$input = $this->_input;

		foreach ($input as $value) {
			$orbits = explode(")", $value);
			if (!array_key_exists($orbits[0], $orbit_list)) {
				$orbit_list[$orbits[0]] = (object)['name' => $orbits[0], 'orbits' => null, 'children' => []];
			}

			if (!array_key_exists($orbits[1], $orbit_list)) {
				$orbit_list[$orbits[1]] = (object)['name' => $orbits[1], 'orbits' => null, 'children' => []];
			}

			//add a new parent*/
			$orbit_list[$orbits[1]]->orbits = $orbit_list[$orbits[0]];
			$orbit_list[$orbits[0]]->children[] = $orbit_list[$orbits[1]];
		}

		$this->orbits = $orbit_list;

		return $orbit_list;
	}

	public function setInput($input) {
		//parse input
		$file = "Day06/".$input;
		$this->_input = InputLoader::LoadAsArrayOfLines($file);
		//$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
		//$this->_input = InputLoader::LoadAsTextBlob($file);
	}
}