<?php
include("configuration.php");

if (!isset($_SESSION["LOGIN_USER"]))
{
	header("Location: http://" . $_SERVER['SERVER_NAME'] . "/subscription_block");
}

$brand = @$_GET["papbrand"];

$numero = @$_GET["papid"];
if (!isset($_GET["papid"]))
{
	die("ERROR: Paper id not defined.");
}
if (!isset($_GET["papbrand"]))
{
	die("ERROR: Paper brand not defined.");
}
$numero = intval($numero);
if (isset($_GET["page"]))
{
	$pageId = intval($_GET["page"]);
}
else
{
	$pageId = -1;
}



?>
<html>
<head>

	<title>Latautuu..</title>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic,700' rel='stylesheet' type='text/css'>
	<link href="/css/bootstrap-slider.css" rel="stylesheet">
    <style>
	body
	{
	background-color: #fffff;
	font-family: 'Open Sans Condensed', sans-serif;
	overflow-x: hidden;
	}
	.paperPlate
	{
	-webkit-box-shadow: 5px 5px 5px 0px rgba(0,0,0,0.75);
-moz-box-shadow: 5px 5px 5px 0px rgba(0,0,0,0.75);
box-shadow: 5px 5px 5px 0px rgba(0,0,0,0.75);
}
.original_size
{
	background-image: url('/html5-reader/images/original-size.png');
	width: 32px;
	height: 32px;
	z-index:10;
	position: fixed;
	top: 3%;
	right: 8%;
	
}
.fit_screen
{
	background-image: url('/html5-reader/images/expand-arrows.png');
	width: 32px;
	height: 32px;
	z-index:10;
	position: fixed;
	top: 3%;
	right: 2%;
}
.leftArrow
{
position: fixed;
top: 0%;
right: 20%;
text-decoration: none;
font-size: 50px;
color: black;
cursor:pointer;
}
.rightArrow
{
position: fixed;
top: 0%;
right: 15%; 
text-decoration: none;
font-size: 50px;
color: black;
cursor:pointer;
}
.frontText
{
 -webkit-touch-callout: none; 
  -webkit-user-select: none;   
  -khtml-user-select: none;    
  -moz-user-select: none;    
  -ms-user-select: none;      
  user-select: none;   
cursor: default;  
  }
  .watermark
  {
  -webkit-transform: rotate(90deg);
-moz-transform: rotate(90deg);
-o-transform: rotate(90deg);
writing-mode: lr-tb;
z-index:10;
  }
  .zoomslider_plate
  {
position: fixed;
top: 45%;
right: 2%; 
background-color: black;
width: 25px;
height: 210px;
padding-top: 5px;
padding-left: 5px;
padding-bottom: 5px;
-moz-border-radius: 7px;
border-radius: 7px;
background: rgba(133, 133, 131, 0.5);
  }
  ::-webkit-scrollbar { 
    display: none; 
}
	</style>
	
	  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	  <script src="https://code.createjs.com/preloadjs-0.6.2.min.js"></script>
	 <script type='text/javascript' src="/js/bootstrap-slider.js"></script>
     <script type="text/javascript">
	
	 var currentPage = <?php echo ($pageId > 0 ? $pageId : "1"); ?>;
	 var totalPages = 60;
	 var ctx;
	 var toolsMoved = false;
	 var loadCompleted = false;
	 var currentPaperId = 1;
	 var currentBrandNam = "????";
	 var currentBrandMarkName = "";
	 var RenderFinished = true;
	 var CurrentScaleFactor = 1;
	 var OriginalSizeMode = false;
	 var PaperMountWidth = 0;
	 var PaperMountHeight = 0;
	 var GraphicLayerCache = {};
	 var zoomMode = false;
	 
	 function loadImage() {
  var preload = new createjs.LoadQueue();
  preload.addEventListener("fileload", handleFileComplete);
  preload.loadFile("assets/preloadjs-bg-center.png");
}
function loadGraphicsToCache(paperBrand, paperId, pageCount)
{ 
        for(gp = 1; gp < pageCount; gp++) {
          GraphicLayerCache[gp] = new Image();
          GraphicLayerCache[gp].onload = function() {
         
          };
          GraphicLayerCache[gp].src = '/assets/paper_parts/' + paperBrand + '/' + paperId + '/' + gp + '.jpg';
        }
      }

	 	 function initReader()
	 {
		  console.log("Init reader");
		  
		  if (!zoomMode)
		  {
			  	 CurrentScaleFactor = document.body.clientWidth / 1024;
		  }
	 
	  $.ajax({
                    url: "/ajax/get_paper_jsondata?brandName=<?php echo $brand; ?>&paperId=<?php echo $numero; ?>",
                    dataType: "text",
                    success: function(data) {
                        var paperData = $.parseJSON(data);
                        $('#paperTitle').html(paperData.paperTitle);
						
					     if (paperData.paperPageCount > 0)
						 {
							$('#totalPageCount').html(paperData.paperPageCount); 
						 }
						 else
						 {
							 $('#totalPageCount').html(''); 
						 }
						 currentPaperId = paperData.paperNumber;
						 currentBrandNam = paperData.brandSystemName;
						 currentBrandMarkName = paperData.brandMarketingName;
						 document.title = currentBrandMarkName + " nro. " + currentPaperId;

						 loadGraphicsToCache(currentBrandNam, currentPaperId, paperData.paperPageCount);
						 RenderText(); 
					
                    },
					error: function (xhr, ajaxOptions, thrownError) {
		window.location = "/paper/" + currentBrandNam +  "/1?error=1";
       // $( "body" ).html('<b style="color: red;">Tapahtui virhe:</b> Lehtitietojen lataaminen epäonnistui. Ole hyvä ja yritä myöhemmin uudelleen.<br><a href="/">Takaisin etusivulle</a>');
      }
                });
	 }
	 
	 function firstLoad()
	 {
	 initReader();
	 }
	 function nextPage()
	 {
	 if (totalPages == currentPage)
	 {
	 return;
	 }
	 if (loadCompleted == false)
	 {
	 return;
	 }
currentPage++;
paperHolder.width  = PaperMountWidth; 
paperHolder.height = PaperMountHeight;
RenderText();

if (currentPage == 2)
{
	$( ".leftArrow" ).show();
}

	 }
	 	 function prevPage()
	 {
	  if (currentPage == 1)
	 {
	 return;
	 }
currentPage--;

 if (currentPage == 1)
	 {
		 $( ".leftArrow" ).hide();
	 ;
	 }
paperHolder.width  = PaperMountWidth; 
paperHolder.height = PaperMountHeight;
RenderText();
	 }
	/* HETKEKSI POIS $( window ).resize(function() {

		 if (OriginalSizeMode)
		 {
			 return;
		 }
		 CurrentScaleFactor = document.body.clientWidth / 1024; 
  paperHolder.width  = PaperMountWidth; 
paperHolder.height = PaperMountHeight;
RenderText();
});
*/
/*	$(document).mousemove(function( event ) {
	 event.stopPropagation();
	
	 $( "#paperTools" ).fadeIn( "slow", function() {
	 setTimeout(function() {
 $('#paperTools').fadeOut("slow");
	}, 2000 );
	
	 });
	
	 });
	 */
		function drawCubicBezier(drawArea, _arg2, _arg3, _arg4, _arg5, _arg6, _arg7, _arg8, _arg9)
	{
			var _local18;
			var letterPlacement;
			var _local21;
			var _local22;
			var CurrentY = (Math.abs(((_arg8 - _arg2) * (_arg8 - _arg2))) + Math.abs(((_arg9 - _arg3) * (_arg9 - _arg3))));
			var lineColor = Math.max(0.1, (1 - (CurrentY / 2000)));
			var NextX = ((_arg8 + (3 * (_arg4 - _arg6))) - _arg2);
			var NextY = ((_arg9 + (3 * (_arg5 - _arg7))) - _arg3);
			var _local14 = (3 * ((_arg2 - (2 * _arg4)) + _arg6));
			var _local15 = (3 * ((_arg3 - (2 * _arg5)) + _arg7));
			var _local16 = (3 * (_arg4 - _arg2));
			var _local17 = (3 * (_arg5 - _arg3));
			var _local20 = lineColor;
			while (_local20 < 1) {
				_local21 = (_local20 * _local20);
				_local22 = (_local21 * _local20);
				_local18 = ((((_local22 * NextX) + (_local21 * _local14)) + (_local20 * _local16)) + _arg2);
				letterPlacement = ((((_local22 * NextY) + (_local21 * _local15)) + (_local20 * _local17)) + _arg3);
				drawArea.lineTo(_local18, letterPlacement);
				_local20 = (_local20 + lineColor);
			};
			drawArea.lineTo(_arg8, _arg9);
		}

 function parseContent(drawArea, vectorData, LocationX, LocationY, penColor)
{
			var tmp;
			var CurrentX;
			var CurrentY;
			var NextX;
			var NextY;
			var _local14;
			var _local15;
			var _local16;
			var cy;
			var _local18;
			var letterPlacement;
			var progress = 0;
			var vectorLength = vectorData.length;
			var HIGH = 65535;
			var START_X = 0;
			var START_Y = 1;
			var PRECISION = 16.0;
			var RADIX = 16;
			var PRE_SCALE_FACTOR = 0.0625;

			while (progress < vectorLength) {
			
				switch (vectorData.charAt(progress)) {
		
					case "B": // Begin drawing
			
						drawArea.beginPath();
				
						break;
					case "F": // Finalize Paint
						drawArea.strokeStyle = lineColor;
						drawArea.closePath();

						drawArea.fillStyle = lineColor;
						drawArea.fill();
						drawArea.stroke();
						break;
					case "S": // Change pen color

						lineColor = "#" + vectorData.substr((progress + 1), 6);
						
						progress = (progress + 6);

						break;
					case "M": // Move pen
			
						tmp = parseInt(vectorData.substr((progress + 1), 8), RADIX);
						CurrentX = (tmp >> PRECISION);
						CurrentY = (tmp & HIGH);
							drawArea.moveTo((CurrentX + LocationX), (CurrentY + LocationY));
									
						progress = (progress + 8);
					
						break;
					case "L":

						tmp = parseInt(vectorData.substr((progress + 1), 8), RADIX);
						CurrentX = (tmp >> RADIX);
						CurrentY = (tmp & HIGH);
				
						drawArea.lineTo((CurrentX + LocationX), (CurrentY + LocationY));
						drawArea.lineWidth = 2;
						progress = (progress + 8);

						break;
					case "C":
						tmp = parseInt(vectorData.substr((progress + 1), 8), RADIX);
						NextX = (tmp >> RADIX);
						NextY = (tmp & HIGH);
						tmp = parseInt(vectorData.substr((progress + 9), 8), RADIX);
						_local14 = (tmp >> RADIX);
						_local15 = (tmp & HIGH);
						tmp = parseInt(vectorData.substr((progress + 17), 8), RADIX);
						_local16 = (tmp >> RADIX);
						cy = (tmp & HIGH);
						drawCubicBezier(drawArea, (CurrentX + LocationX), (CurrentY + LocationY), (NextX + LocationX), (NextY + LocationY), (_local14 + LocationX), (_local15 + LocationY), (_local16 + LocationX), (cy + LocationY));
						CurrentX = _local16;
						CurrentY = cy;
						progress = (progress + 24);
						break;
					case "G":
						_local18 = ((vectorData.indexOf("!", (progress + 1)) - progress) - 1);
						letterPlacement = vectorData.substr((progress + 1), _local18).split("#");
						progress = (progress + _local18);
						
						parseContent(drawArea, vectorData.substr(letterPlacement[0], letterPlacement[1]), parseInt(letterPlacement[2]), parseInt(letterPlacement[3]), lineColor);
						break;
				}
				progress++;
			}
		}
		
		
		  RenderText = function (){
			  
		  if (RenderFinished == false)
		  {
			  return;
		  }
		  RenderFinished = false;
$("#loadingAnimation").show();
  $('.pageNumber').html(currentPage);
  loadCompleted = false;
  
  $.ajax({
    url:'/assets/paper_parts/' + currentBrandNam + '/' + currentPaperId + '/' + currentPage + '.vec',
    success: function (data){
	
	
		var vectorIndex = data.indexOf("!");
			var paperDimensionArray = data.substr(0, vectorIndex).split("#");
			var paperWidth = (paperDimensionArray[2] * CurrentScaleFactor);
			var paperHeight = (paperDimensionArray[3] * CurrentScaleFactor);
			
	var c = document.getElementById("paperHolder");
ctx = c.getContext("2d");
paperHolder.width  = paperWidth; 
paperHolder.height = paperHeight;

PaperMountWidth = paperWidth;
PaperMountHeight = paperHeight;

if (GraphicLayerCache[currentPage] != null)
{
	 ctx.drawImage(GraphicLayerCache[currentPage], 0, 0, paperDimensionArray[2] * CurrentScaleFactor, paperDimensionArray[3] * CurrentScaleFactor);
ctx.scale(0.0625 * CurrentScaleFactor, 0.0625 * CurrentScaleFactor);
			  parseContent(ctx, data, 0, 0, 0);
			   
			      ctx.globalCompositeOperation="destination-over";
				  
			   $("#loadingAnimation").hide();
			 loadCompleted = true;
			 RenderFinished = true;
			 document.body.style.background = '#afaeac';
			 $( ".rightArrow" ).show();
}

	
	 	   graphicLayer = new Image();

 graphicLayer.src = '/assets/paper_parts/' + currentBrandNam + '/' + currentPaperId + '/' + currentPage + '.jpg';
graphicLayer.onload = function(){

    ctx.drawImage(graphicLayer, 0, 0, paperDimensionArray[2] * CurrentScaleFactor, paperDimensionArray[3] * CurrentScaleFactor);
ctx.scale(0.0625 * CurrentScaleFactor, 0.0625 * CurrentScaleFactor);
			  parseContent(ctx, data, 0, 0, 0);
			   
			      ctx.globalCompositeOperation="destination-over";
				   $( "#zoomMask" ).show();
			   $("#loadingAnimation").hide();
			 loadCompleted = true;
			 RenderFinished = true;
			 document.body.style.background = '#afaeac';
			 $( ".rightArrow" ).show();

}
  
    },
	error: function (xhr, ajaxOptions, thrownError) {
		window.location = "/paper/" + currentBrandNam +  "/1?error=1";
        //$( "body" ).html('<b style="color: red;">Tapahtui virhe:</b> Lehtitietojen lataaminen epäonnistui. Ole hyvä ja yritä myöhemmin uudelleen.<br><a href="/">Takaisin etusivulle</a>');
      }

  });
 

  $("#originalSize").click(function() {
OriginalSizeMode = true;
  CurrentScaleFactor = 1;
  paperHolder.width  = 2000; 
paperHolder.height = 2000;
RenderText();
  return false;
});

  $("#fitScreen").click(function() {
	  OriginalSizeMode = false;
  CurrentScaleFactor = document.body.clientWidth / 1024; // calculate
  paperHolder.width  = 2000; 
paperHolder.height = 2000;
RenderText();
  return false;
});

$("#zoomSlider").slider({
	reversed : true,
});

var originalVal;

$('#zoomSlider').slider().on('slideStart', function(ev){
    originalVal = $('#zoomSlider').data('slider').getValue();
});

$('#zoomSlider').slider().on('slideStop', function(ev){
	
	if (!zoomMode)
	{
		$( "#paperHolder" ).wrap( '<div style="width: auto; height: auto; overflow-x: scroll; overflow-y: hidden;" id="zoomMask"></div>' ); // wrapataan
		zoomMode = true; // laitetaan zoomaus tila käyttöön
	}
    var newVal = $('#zoomSlider').data('slider').getValue();
	
	/*if (newVal == 1)
	{
		$( "#paperHolder" ).unwrap( '<div style="width: auto; height: auto; overflow-x: scroll; overflow-y: hidden;" id="zoomMask"></div>' ); // wrapataan
		zoomMode = false;
		return false;
	}
	*/
	
	$( "#zoomMask" ).hide();
	
	 CurrentScaleFactor = newVal;
	 paperHolder.width  = 100; 
paperHolder.height = 100;

	 initReader();
	 
});

var scroller = 0;
var curerx = 0;
$( "#zoomMask" ).mousemove(function( event ) {
	
	$( "#zoomMask" ).scrollLeft(scroller);
	
	if (curerx < event.pageX)
	{
		scroller++;
	}
	else
	{
scroller--;
	}
	curerx = event.pageX;

});

}
// drawVector(s, 'aa', 0, 0, 0);
     </script>
</head>
<body onload="firstLoad();" style="z-index:1;">

<canvas id="paperHolder" style="box-shadow: 0 5px 5px #858985; background-color: white;  padding-left: 0;
    padding-right: 0;
    margin-left: auto;
    margin-right: auto;
    display: block; cursor:default;"></canvas>
	
<div style="z-index:2;" id="paperTools">

<a class="leftArrow" onclick="prevPage();" style="display: none;">←</a>
<a class="rightArrow" onclick="nextPage();" style="display: none;">→</a>
<div class="zoomslider_plate">
<input id="zoomSlider" type="text" data-slider-min="0.1" data-slider-max="2" data-slider-step="0.1" data-slider-value="1" data-slider-orientation="vertical"/>
</div>

<span style="position: fixed; top: 5%; left:48%; font-size: 13px;" class="frontText" style="display: none;"><div class="pageNumber" style="display: inline-block;">1</div>/<span id="totalPageCount">??</span></span>

<span style="position:fixed; top:2%; left:45%; font-size: 15px; color: #686a69;" class="frontText" id="paperTitle" ></span>
<a href="" id="fitScreen" title="Skaalaa ikkunaan sopivaksi"><div class="fit_screen"></div></a>
<a href="" id="originalSize" title="Todellinen koko"><div class="original_size"></div></a>

</div>

<img src="/html5-reader/images/ajax-loader.gif" style="z-index:2; position:absolute; top:50%; left:45%; display: none;" id="loadingAnimation">


 <!-- <textarea name="debugConsole" id="debugConsole" rows="20" cols="100" style="position: absolute; top: 500px;"></textarea> -->
</body>
</html>
