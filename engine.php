<?php

// Vector imaging module

header('Content-Type: image/png');

$vectorFile = file_get_contents("1.vec");

function char_at($str, $pos)
{
  return $str[$pos];
}



function drawVector($drawArea, $vectorData, $LocationX, $LocationY, $penColor = 0) {

			$_local8;
			$_local9;
			$_local10;
			$_local12;
			$_local13;
			$_local14;
			$_local15;
			$_local16;
			$_local17;
			$_local18;
			$_local19; // Array
			$lastLocX = 0;
			$lastLocY = 0;
			$CurrentLocation = 0;
			$vectorLength = strlen($vectorData);
			$lineColor = $penColor;
		
			while ($CurrentLocation < $vectorLength) {
		
			if ($CurrentLocation > 1000)
			{
				break;
			}
				switch (char_at($vectorData, $CurrentLocation)) {
					case "B":
					
				//	echo "Aloitetaan piirto<br>";
					
						//graphic.beginFill($lineColor);
						break;
					case "F":
					
				//	echo "Päätetään piirto<br>";
					
						//graphic.endFill();
						break;
					case "S":
					//echo "Vaihdetaan kynän väri<br>";
						$lineColor = "0x" + substr($vectorData, ($CurrentLocation + 1), 6);
						$CurrentLocation = ($CurrentLocation + 6);
//echo "Vaihdetaan väri";
						break;
					case "M":
					//echo "Siirretään viivaa.<br>";
						$_local8 = substr($vectorData, ($CurrentLocation + 1), 8);
						$_local9 = ($_local8 >> 16);
						$_local10 = ($_local8 & 0xFFFF);
						$lastLocX = ($_local9 + $LocationX);
						$lastLocY =($_local10 + $LocationY);
						//graphic.moveTo(($_local9 + $LocationX), ($_local10 + $LocationY));
						$CurrentLocation = ($CurrentLocation + 8);
						break;
					case "L":
				//	echo "Piirretään viiva<br>";
						$_local8 = substr($vectorData, ($CurrentLocation + 1), 8);
						$_local9 = ($_local8 >> 16);
						$_local10 = ($_local8 & 0xFFFF);
						//graphic.lineTo(($_local9 + $LocationX), ($_local10 + $LocationY));
						$black = imagecolorallocate($drawArea, 0, 0, 0);
						imageline($drawArea, $lastLocX, $lastLocY, ($_local9 + $LocationX), ($_local10 + $LocationY), $black);
						$CurrentLocation = ($CurrentLocation + 8);
						break;
					case "C":
				//	echo "Piirretään monikulmio<br>";
						$_local8 = substr($vectorData, ($CurrentLocation + 1), 8);
						$_local12 = ($_local8 >> 16);
						$_local13 = ($_local8 & 0xFFFF);
						$_local8 = substr($vectorData, ($CurrentLocation + 9), 8);
						$_local14 = ($_local8 >> 16);
						$_local15 = ($_local8 & 0xFFFF);
						$_local8 = substr($vectorData, ($CurrentLocation + 17), 8);
						$_local16 = ($_local8 >> 16);
						$_local17 = ($_local8 & 0xFFFF);
						//drawCubicBezier(graphic, ($_local9 + $LocationX), ($_local10 + $LocationY), ($_local12 + $LocationX), ($_local13 + $LocationY), ($_local14 + $LocationX), ($_local15 + $LocationY), ($_local16 + $LocationX), ($_local17 + $LocationY));
						$_local9 = $_local16;
						$_local10 = $_local17;
						$CurrentLocation = ($CurrentLocation + 24);
						break;
					case "G":
				//	echo "Luodaan kaksinaisvektori<br>";
						$_local18 = ((stripos($vectorData, "!") - $CurrentLocation) - 1);
						$_local19 = explode("#" ,substr($vectorData, ($CurrentLocation + 1), $_local18));
						$CurrentLocation = ($CurrentLocation + $_local18);
						//drawVector(graphic, substr($vectorData, $_local19[0], $_local19[1]), $_local19[2]), $_local19[3]), $lineColor);
						break;
				}

				$CurrentLocation++;
			}
		}
		
$vectorIndex = stripos($vectorFile, "!");
$paperDimensionArray = explode("#", substr($vectorFile, 0, $vectorIndex));

$imageHolder = imagecreatetruecolor($paperDimensionArray[2], $paperDimensionArray[3]);
$red = imagecolorallocate($imageHolder, 255, 155, 255);
imagefill($imageHolder, 0, 0, $red);

$textcolor = imagecolorallocate($imageHolder, 0, 0, 0);

imagestring($imageHolder, 25, 0, 0, 'Pohjalainen - 21.6.2016', $textcolor);

drawVector($imageHolder, $vectorFile, 0, 0, 0);

imagepng($imageHolder);

?>