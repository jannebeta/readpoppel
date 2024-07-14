<?php
// ReadPoppel - Vector Render

echo vectorFileLoaded(1, file_get_contents("deb/1.vec"), "http://localhost/deb/1.jpg", "");
function addPathString($binary, $htmlString, $offsetX, $offsetY, $fillColor ) {
    $index = 0;
    $length = strlen($binary);

    $x;
	$y;
	$x1;
	$y1;
	$cx;
	$cy;
	$x2;
	$y2;
    $m;

    $path = "";
    $data = "";
    if( !$fillColor ) {
        $fillColor = "#000000";
    }

    while ($index < $length) {
        switch($binary[$index]) {
            case "B" : {
                if( strlen($path) > 0 ) {
                    $path += " d='" + $data + " Z' />";
                    $htmlString += $path;
                }

                $data = "";
                $path = "  <path style='fill:" + $fillColor + ";'";
                break;
            }

            case "F" : {
                break;
            }

            case "S" : {
                $fillColor = "#" + $binary.substr($index+1, 6);
                $index += 6;
                break;
            }

            case "M" : {
                $m = parseInt($binary.substr($index+1, 8), 16);
                $x = $m >> 16;
                $y = $m & 0xFFFF;

                if( $data.length > 0 ) {
                    $data += " ";
                }

                $data += "M " + ($x+$offsetX) + " " + ($y+$offsetY);
                $index += 8;
                break;
            }

            case "L" : {
                $m = intval($binary.substr($index+1, 8), 16);
                $x = $m >> 16;
                $y = $m & 0xFFFF;

                $data += " L " + ($x+$offsetX) + " " + ($y+$offsetY);
                $index += 8;
                break;
            }

            case "C" : {
                $m = intval($binary.substr($index+1, 8), 16);
                $x1 = ($m >> 16);
                $y1 = ($m & 0xFFFF);

                $m = intval($binary.substr($index+9, 8), 16);
                $cx = ($m >> 16);
                $cy = ($m & 0xFFFF);

                $m = intval($binary.substr($index+17, 8), 16);
                $x2 = ($m >> 16);
                $y2 = ($m & 0xFFFF);

                $data += " C " + ($x1 + $offsetX) + " " + (y1+$offsetY) + " " + ($cx + $offsetX) + " " + (cy+offsetY) + " " + (x2 + offsetX) + " " + (y2+offsetY);
                $index += 24;
                break;
            }

            case "G" : {
                $glyphLength = $binary.indexOf("!", $index+1)-$index-1;
                $elements = $binary.substr($index+1, $glyphLength).split("#");

                if( strlen($path) > 0 ) {
				$path += " d='" + $data + " Z' />";
                    $htmlString += $path;
                    $path = "";
                    $data = "";
                }

                $htmlString = addPathString( $binary.substr($elements[0], $elements[1]), $htmlString, parseInt($elements[2]), parseInt($elements[3]), $fillColor );

                $index += $glyphLength;
                break;
            }
        }

        $index++;
    }

    if( strlen($path) > 0 ) {
        $path += " d='" + $data + " Z' />";
        $htmlString += $path;
        $data = "";
        $path = "";
    }
    return $htmlString;
}

function vectorFileLoaded($page, $vectorData, $backgroundPath, $cacheString ) {
    $headerLength = strrpos ($vectorData, "!");
    $headerElements = ($vectorData.substr(0, ($headerLength).split("#");

    $width = intval($headerElements[2]);
    $height = intval($headerElements[3]);

    $parsedData;
    $parsedData["pageNumber"] = $page;
    $parsedData["width"] = $width;
    $parsedData["height"] = $height;

   $viewBox = "";
    $imageSize = "width='100%' height='100%'";
    if($tr > $headerLength + 1 ) {
        $viewBox = "viewBox='0 0 " + ($width * 16 + " " + ($height * 16 + "'";
        $imageSize = "width='" +( ($width * 16 + "' height='" + ($height * 16 + "'";
    }

    $htmlString = "<svg data-page='" + $page + "' xmlns='http://www.w3.org/2000/svg' " + $viewBox + ">";
    $htmlString += "  <image xmlns:xlink='http://www.w3.org/1999/xlink' xlink:href='" + $backgroundPath + "' x='0' y='0' " + $imageSize + "/>";

    $start = time();
    $htmlString = addPathString($vectorData, $htmlString, 0, 0 );
    $end = time();

    $htmlString += "</svg>";

    $parsedData['html'] = $htmlString;
    $parsedData['total_time'] = time();
    $parsedData['vector_time'] = $end - $start;
   
       return $parsedData['html'];
 
    return $parsedData;
}
?>