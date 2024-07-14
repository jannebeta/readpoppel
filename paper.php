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

<title>Lukupoppeli</title>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="/js/jquery.panzoom.js"></script>
<script src="/js/jquery.mousewheel.js"></script>
<script src="http://hammerjs.github.io/dist/hammer.min.js"></script>
<script src="/js/jquery.hammer.js"></script>
<script src="/js/jquery.imagemapster.min.js"></script>
<script src="/assets/js/jquery.svg.pan.zoom.js"></script>

<script src="/js/bootstrap-slider.js"></script>
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/bootstrap-slider.css" rel="stylesheet">

<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic,700' rel='stylesheet' type='text/css'>

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.9/css/all.css" integrity="sha384-5SOiIsAziJl6AWe0HWRKTXlfcSHKmYV4RBF18PPJ173Kzn7jzMyFuTtk8JA7QQG1" crossorigin="anonymous">

 <style>
	body
	{

	background-color: #afaeac;
	font-family: 'Open Sans Condensed', sans-serif;
	}
	.nextBtn
	{
		cursor: pointer;
		height: 4%;
		padding: 5px;
		position: absolute;
		top: 1%;
		left: 90%;
	}
	.previousBtn
	{
		cursor: pointer;
		padding: 5px;
		position: absolute;
		top: 1%;
		left: 5%;
		
	}
	.zoomInBtn
	{
		width: 7%;
		cursor: pointer;
		height: 5%;
		padding: 5px;
		position: absolute;
		top: 75%;
		left: 95%;
	}
	.zoomResetBtn
	{
		cursor: pointer;
		padding: 5px;
		position: absolute;
		top: 83%;
		left: 90%;
	}
	.zoomOutBtn
	{
		
		cursor: pointer;
		padding: 5px;
		position: absolute;
		top: 83%;
		left: 95%;
	
	}
	#paperHolder
	{
		background-color: white;
	-webkit-box-shadow: 5px 5px 5px 0px rgba(0,0,0,0.75);
-moz-box-shadow: 5px 5px 5px 0px rgba(0,0,0,0.75);
box-shadow: 5px 5px 5px 0px rgba(0,0,0,0.75);
}
.pageListOverlay{
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 10;
  background-color: rgba(0,0,0,0.70); /*dim the background*/
}

.triangle-right {
    display: inline-block;
    width: 0;
    height: 0;
	position: fixed;
	top: 40%;
	right: 2%;
    border-style: solid;
    border-width: 60px 0 60px 50px;
	cursor: pointer;
    border-color: transparent transparent transparent #767978;
	filter:drop-shadow(0 2px 4px rgba(0,0,0,.5))

}
#zoomControl
{
	display: inline-block;
    width: 0;
    height: 0;
	position: fixed;
	top: 60%;
	left: 20%;
}
.triangle-left {
    display: inline-block;
    width: 0;
    height: 0;
	position: fixed;
	top: 40%;
	left: 2%;
    border-style: solid;
    border-width: 60px 50px 60px 0;
	cursor: pointer;
    border-color: transparent #767978 transparent transparent;
filter:drop-shadow(0 2px 4px rgba(0,0,0,.5))

}

</style>
<script>
	 var currentPage = <?php echo ($pageId > 0 ? $pageId : "1"); ?>;
	 var totalPages = 20;
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
	 function initReader()
	 {
		  console.log("Init reader");
	  $.ajax({
                    url: "/ajax/get_paper_jsondata?brandName=<?php echo $brand; ?>&paperId=<?php echo $numero; ?>",
                    dataType: "text",
                    success: function(data) {
                        var paperData = $.parseJSON(data);
						$('#paperTitle').html(paperData.paperTitle);



						 currentPaperId = paperData.paperNumber;
						 currentBrandNam = paperData.brandSystemName;
						 currentBrandMarkName = paperData.brandMarketingName;
						 document.title = currentBrandMarkName + " nro. " + currentPaperId;
  if (paperData.paperPageCount > 0)
						 {
							 totalPages = paperData.paperPageCount;
							$('#totalPages').html(paperData.paperPageCount);

						 }
						 else
						 {
							 $('#totalPages').html('');
						 }

						loadPage(currentBrandNam, currentPaperId,  ((currentPage > 0) ? currentPage : 1));

						// loadGraphicsToCache(currentBrandNam, currentPaperId, paperData.paperPageCount);
					//	 RenderText();

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

	 function loadPage(brandCode, paperId, page)
	 {
		 if (page < 0)
		 {
			return;
		 }

		 console.log("Loading page #" + page + " on paper: " + brandCode + " nmbr: " + paperId);

		 var pageLoader = $.get( "/assets/paper_parts/" + brandCode + "/" + paperId + "/" + page + ".vec", function(data) {
  $( "#paperHolder" ).html(vectorFileLoaded(page, data, "/assets/paper_parts/" + brandCode + "/" + paperId + "/" + page + ".jpg", ""));
  $( "#currentPage" ).html(page);

	var holder = $("#paperContent").svgPanZoom();

$( ".zoomInBtn" ).click(function(e) {
holder.zoomIn();
});
$( ".zoomOutBtn" ).click(function(e) {
holder.zoomOut();
});
$( ".zoomRestBtn" ).click(function(e) {
holder.reset();
});


})
  .done(function() {



  })
  .fail(function() {
   window.location = "/paper/" + currentBrandNam +  "/1?error=1";
  })
  .always(function() {

  });
	 }
	   var currentZoom = 20.0;


$( document ).ready(function() {

	/*$("#paperHolder").hammer().on("swipeup", function() {
		$('#paperHolder').animate({
				'marginTop' : "+=100px" //moves down
				});
	});

$("#paperHolder").hammer().on("swipedown", function() {
	$('#paperHolder').animate({
			'marginTop' : "-=100px" //moves down
			});
});
*/
$( ".previousBtn" ).click(function(e) {
	if (currentPage == 1)
	{
	 // ollaan jo ekalla sivulla
	 return;
	}
	currentPage--;
	loadPage(currentBrandNam, currentPaperId, currentPage);
});
$( ".nextBtn" ).click(function(e) {
	currentPage++;
	loadPage(currentBrandNam, currentPaperId, currentPage);
});
$("#paperHolder").hammer().on("swipeleft", function() {
	if (currentPage == 1)
 {
	 // ollaan jo ekalla sivulla
	 return;
 }
 currentPage--;
 loadPage(currentBrandNam, currentPaperId, currentPage);
	console.log("Edelliselle sivulle..");
});
$("#paperHolder").hammer().on("swiperight", function() {
	// seuraavalle sivulle!
	currentPage++;
	loadPage(currentBrandNam, currentPaperId, currentPage);
	console.log("Seuraavalle sivulle..");
});
$("#paperHolder").hammer().on("press", function() {
	$( ".pageListOverlay" ).show();
	generatePageList();

});





	$( ".pageListOverlay" ).click(function(e) {
$( ".pageListOverlay" ).hide();
$('html, body').css('overflowY', 'scroll');
});

});

function vectorFileLoaded( page, vectorData, backgroundPath, cacheString ) {
    var headerLength = vectorData.indexOf("!");
    var headerElements = vectorData.substr(0, headerLength).split("#");

    var width = parseInt(headerElements[2]);
    var height = parseInt(headerElements[3]);

    var parsedData = {};
    parsedData["pageNumber"] = page;
    parsedData["width"] = width;
    parsedData["height"] = height;

    var viewBox = "";
    var imageSize = "width='100%' height='100%'";
    if( vectorData.length > headerLength + 1 ) {
        viewBox = "viewBox='0 0 " + width * 16 + " " + height * 16 + "'";
        imageSize = "width='" + width * 16 + "' height='" + height * 16 + "'";
    }

    var htmlString = "<svg data-page='" + page + "' xmlns='http://www.w3.org/2000/svg' " + viewBox + " id='paperContent'>";
    htmlString += "  <image xmlns:xlink='http://www.w3.org/1999/xlink' xlink:href='" + backgroundPath + "' x='0' y='0' " + imageSize + "/>";

    var start = new Date().getTime();
    htmlString = addPathString( vectorData, htmlString, 0, 0 );
    var end = new Date().getTime();

    htmlString += '</svg>';

    parsedData['html'] = htmlString;
    parsedData['total_time'] = new Date().getTime();
    parsedData['vector_time'] = end - start;
    try {
       return parsedData['html'];
    } catch(error) {
alert(error);
    }
    return parsedData;
}

function addPathString( binary, htmlString, offsetX, offsetY, fillColor ) {
    var index = 0;
    var length = binary.length;

    var x,y, x1, y1, cx, cy, x2, y2;
    var m;

    var path = "";
    var data = "";
    if( !fillColor ) {
        fillColor = "#000000";
    }

    while (index < length) {
        switch(binary.charAt(index)) {
            case "B" : {
                if( path.length > 0 ) {
                    path += " d='" + data + " Z' />";
                    htmlString += path;
                }

                data = "";
                path = "  <path style='fill:" + fillColor + ";'";
                break;
            }

            case "F" : {
                break;
            }

            case "S" : {
                fillColor = "#" + binary.substr(index+1, 6);
                index += 6;
                break;
            }

            case "M" : {
                m = parseInt(binary.substr(index+1, 8), 16);
                x = m >> 16;
                y = m & 0xFFFF;

                if( data.length > 0 ) {
                    data += " ";
                }

                data += "M " + (x+offsetX) + " " + (y+offsetY);
                index += 8;
                break;
            }

            case "L" : {
                m = parseInt(binary.substr(index+1, 8), 16);
                x = m >> 16;
                y = m & 0xFFFF;

                data += " L " + (x+offsetX) + " " + (y+offsetY);
                index += 8;
                break;
            }

            case "C" : {
                m = parseInt(binary.substr(index+1, 8), 16);
                x1 = (m >> 16);
                y1 = (m & 0xFFFF);

                m = parseInt(binary.substr(index+9, 8), 16);
                cx = (m >> 16);
                cy = (m & 0xFFFF);

                m = parseInt(binary.substr(index+17, 8), 16);
                x2 = (m >> 16);
                y2 = (m & 0xFFFF);

                data += " C " + (x1 + offsetX) + " " + (y1+offsetY) + " " + (cx + offsetX) + " " + (cy+offsetY) + " " + (x2 + offsetX) + " " + (y2+offsetY);
                index += 24;
                break;
            }

            case "G" : {
                var glyphLength = binary.indexOf("!", index+1)-index-1;
                var elements = binary.substr(index+1, glyphLength).split("#");

                if( path.length > 0 ) {
                    path += " d='" + data + " Z' />";
					htmlString += path;
                    path = "";
                    data = "";
                }

                htmlString = addPathString( binary.substr(elements[0], elements[1]), htmlString, parseInt(elements[2]), parseInt(elements[3]), fillColor );

                index += glyphLength;
                break;
            }
        }

        index++;
    }

    if( path.length > 0 ) {
        path += " d='" + data + " Z' />";
        htmlString += path;
        data = "";
        path = "";
    }
    return htmlString;
}
function generatePageList()
{
	$( "#pageList" ).html("");
	for (i = 1; i <= totalPages; i++) {
		$( "#pageList" ).append( "<div class=\"col-lg-2 col-md-4 col-xs-6 thumb\"><a class=\"thumbnail\" page=\"" + i + "\" href=\"#\" style=\"background-color: transparent;  border: 0 none; text-decoration: none; display: inline-block;\"><img class=\"img-responsive\" style=\"height: 180px; -webkit-box-shadow: 3px 3px 5px 0px rgba(0,0,0,0.75); -moz-box-shadow: 3px 3px 5px 0px rgba(0,0,0,0.75); box-shadow: 3px 3px 5px 0px rgba(47,48,47,0.75);\" src=\"http://www.e-pages.dk/" + currentBrandNam + "/" + currentPaperId + "/pic/tm" + i + ".jpg\" alt=\"\"><div style=\"font-family: Open Sans Condensed; font-weight: 300; position:relative; text-align:center; height: 30px;  width: 100px; left: 12px; top: -105px; background-color: white; font-size: 20px; opacity: 0.95; color: black; -webkit-border-radius: 6px -moz-border-radius: 6px; border-radius: 6px;\">Sivu " + i + "</div></a></div>" );
	}
	$('html, body').css('overflowY', 'hidden');
	console.log("Luodaan sivulistaus.");
	$('.thumbnail').click(function() {
   loadPage(currentBrandNam, currentPaperId, $(this).attr('page'));
   return false;
});
}
</script>

</head>

<body onload="firstLoad();">

<div class="pageListOverlay" style="display: none;">
 <div class="container-fluid" style="padding-top: 2%; height: 100%; overflow-x: hidden; overflow-y: scroll;">
<div class="row" id="pageList">

			</div></div>
</div>
<div id="controlBar" style="margin: 0 auto; position: fixed; top: 0px; left: 0px; height: 50px; width: 100%;"><div id="paperTitle" style="position: fixed; left: 43%;"></div><div id="pageCount" style="position: fixed; left: 43%; top: 4%;"><span id="currentPage"></span>/<span id="totalPages"></span></div><a href="#" id="showPageList"><i class="fas fa-clone" style="font-size: 3em; padding-left: 2%; padding-top: 1%;" onclick="$('.pageListOverlay').show(); generatePageList(); return false;"></i></a> 
<!-- <div class="triangle-left" id="prevPage"></div>
<div class="triangle-right" id="nextPage"></div> -->

</div>

<i class="previousBtn fas fa-angle-double-left" style="font-size:4em; text-shadow: 0px 0px 5px #FFFFFF;"></i>
<i class="nextBtn fas fa-angle-double-right" style="font-size:4em; text-shadow: 0px 0px 5px #FFFFFF;"></i>
<i class="zoomInBtn fas fa-search-plus" style="font-size:4em; text-shadow: 0px 0px 5px #FFFFFF;"></i>

<i class="zoomOutBtn fas fa-search-minus" style="font-size:4em; text-shadow: 0px 0px 5px #FFFFFF;"></i>
<div id="paperHolder"></div>
<script>


</script>
</body>
</html>
