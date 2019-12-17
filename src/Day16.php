<?php 
class Day16 implements iDay
{
	private $_input;

	public function __construct($input) {
		$this->setInput($input);
	}

	public function runPart1() {
		return 0;
		$inp = str_split($this->_input);
		//$inp = str_split('80871224585914546619083218645595');
		//$line =  new \Ds\Queue($inp);
		$line =  $inp;
		$pat = [0, 1, 0, -1];

		for ($st = 0; $st < 100; $st++) {
			print("STEP $st \n");
			$new = [];
			for($i = 1; $i <= count($inp); $i++) {
				$j = $i - 1;
				$total = 0;
				$count = 0;
				while ($count < count($line)) {
					foreach ($pat as $p) {
						if ($count >= count($line))
							break;

						while ($j > 0) {
							if ($count >= count($line))
								break;

							$n = $line[$count];
							//print("$n $p ".  $n * $p . "\n");
							$total += $n * $p;
							$j--;
							$count++;
						}
						$j = $i;
						# code...
					}
				}
				//print("$total \n");
				//break;
				//print(substr((string)$total, -1) . "\n");
				$new[] = substr((string)$total, -1);
			}
			$line = $new;
			//print(implode('', $new) ."\n");
		}
		//var_dump($new);
		$res = implode('', $line); 
		return substr($res, 0, 8);
	}

	public function runPart2() {
		$line = "";

		for ($k = 0; $k < 10000; $k++) {
			//$line .= '03036732577212944063491565474664';
			$line .= $this->_input;
		}
		$sub = substr($line, 0, 7);
		$line = substr($line, $sub);
		$line = str_split($line);

		for ($st = 0; $st < 100; $st++) {
			$totals = [];
			$last = array_sum($line);
			$totals[] =  substr((string)$last, -1);

			for($i = 1; $i <= count($line); $i++) {
				$last -= $line[$i-1];

				$totals[] = substr((string)$last, -1);
			}
			$line = $totals;
		}

		return substr(implode('',$line), 0 , 8);
	}

	public function setInput($input) {
		//parse input
		$file = "Day16/".$input;
		//$this->_input = InputLoader::LoadAsArrayOfLines($file);
		//$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
		$this->_input = InputLoader::LoadAsTextBlob($file);
	}

}