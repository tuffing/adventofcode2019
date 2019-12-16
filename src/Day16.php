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
		//return(substr('98765432109876543210', 7, 8));
		$line = "";
		$sub = (int)substr($line, 0, 7);

		for ($k = 0; $k < 7; $k++) {
			//$line .= '03036732577212944063491565474664';
			$line .= $this->_input;
		}

		//$line = str_split($line);
		//$inp = str_split('80871224585914546619083218645595');
		//$line =  new \Ds\Queue($inp);
		$pat = [0, 1, 0, -1];

		for ($st = 0; $st < 100; $st++) {
			print("STEP $st \n");
			$new = "";
			for($i = 1; $i <= strlen($line); $i++) {
				$j = $i - 1;
				$total = 0;
				$count = 0;
				while ($count < strlen($line)) {
					foreach ($pat as $p) {
						if ($count >= strlen($line))
							break;

						while ($j > 0) {
							if ($count >= strlen($line))
								break;

							$n = substr($line, $count, 1);//$line[$count];
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
				$new .= substr((string)$total, -1);
			}
			$line = $new;
			//$test = sub_str($line, -(651*2));
			//print("$test \n");
		}
		//var_dump($new);
		//$sub = (int)substr($line, 0, 7);
		//$res = implode('', $line); 
		//file_put_contents("day16.txt", $res);
		print("$line \n");

		$test = substr($line, -(651*2));
		print("$test \n");
		return substr($line, $sub , 8);
	}

	public function setInput($input) {
		//parse input
		$file = "Day16/".$input;
		//$this->_input = InputLoader::LoadAsArrayOfLines($file);
		//$this->_input = InputLoader::FindAndLoadAllNumbersIntoArray($file);
		$this->_input = InputLoader::LoadAsTextBlob($file);
	}

}