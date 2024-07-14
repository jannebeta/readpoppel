 <!DOCTYPE html>
<html lang="fi">
 <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>LukuPoppeli v2 BETA</title>

    <meta name="description" content="Sanomalehtien taivas">
    <meta name="generator" content="ReadPoppel v2 BETA">

	<link rel="apple-touch-icon" sizes="57x57" href="/assets/favicons/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/assets/favicons/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/assets/favicons/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/assets/favicons/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/assets/favicons/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/assets/favicons/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/assets/favicons/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/assets/favicons/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/assets/favicons/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/assets/favicons/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/favicons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/assets/favicons/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/assets/favicons/favicon-16x16.png">
<link rel="manifest" href="/assets/favicons/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/assets/favicons/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">

	<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic,700' rel='stylesheet' type='text/css'>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.9/css/all.css" integrity="sha384-5SOiIsAziJl6AWe0HWRKTXlfcSHKmYV4RBF18PPJ173Kzn7jzMyFuTtk8JA7QQG1" crossorigin="anonymous">
	<style>
	.attention
	{
		width: 50px;
height: 50px;
border-style: solid;
border-width: 0 0 70px 70px;
border-color: transparent transparent #ff3c00 transparent;
position: absolute;
top: 54%;
left: 48%;
	}
	.rotation {
  color: white;
  position: absolute;
  top: 29px;
  right: 5px;
  font-size: 12px;
  -webkit-transform: rotate(100deg);
  -moz-transform: rotate(320deg);
  -ms-transform: rotate(320deg);
  -o-transform: rotate(320deg);
  transform: rotate(315deg);

}
	</style>
	 <script src="/js/jquery.min.js"></script>
	 <script src="/js/jquery.mask.js"></script>
   <script src="/js/jquery.hammer.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/scripts.js"></script>
	<script src="/js/admin_functions.js"></script>
	<script type="text/javascript">
	function imageError(image) {
    image.onerror = "";
    image.src = "/assets/images/placeholder.png";
    return true;
}

		function loadRandomPapers()
		{
	$.ajax({
    type: 'GET',
    url: '/dynamic_content/randompapers.json',
    data: { amount: '8' },
    dataType: 'json',
	 error: function () {

$('#randomPapersContainer').html("Satunnaisten lehtien noutaminen epäonnistui.");

    }
,
    success: function (data) {
	 $('#randomPapersContainer').html("");
        $.each(data, function(index, rPap) {
            $('#randomPapersContainer').append('<a href="/paper/' + rPap.paperBrand + '/read/' + rPap.paperId + '"><img src="http://www.e-pages.dk/' + rPap.paperBrand + '/' + rPap.paperId + '/pic/tm1.jpg" style="height: 130px; margin-right: 20px;" onError="imageError(this);" id="randomPaper" data-toggle="tooltip" title="' + rPap.paperTitle + '"></a>');
        });
    }
});
}

<?php
if (@$brand != NULL)
{
?>

function loadFirstPage()
{
	$.ajax({
	method:'post',
    url:'/json_paperpaging.php',
	data: {
        'page': '<?php echo (isset($_GET["page"]) ? intval($_GET["page"]) : "0"); ?>',
        'paper': '<?php echo $brand; ?>'
    },
    success: function (data){

	//$("#results").html("data");

$("#results").empty();

var pageData = JSON.parse(JSON.stringify(data));

for (i = 0; i < pageData.items.length; i++)
{
  //$("#results").append(pageData.items[i].id);
  var paperId = pageData.items[i].id;
  var publishedDate = new Date(pageData.items[i].published*1000);
  var publishedPrettyDate = publishedDate.getDate() + "." + (publishedDate.getMonth()+1) + "." + publishedDate.getFullYear();
  var papBrand = pageData.paperBrand;
  var bColor = pageData.brandColor;
  var tColor = (bColor == "#ffffff" ? "black" : "white");
  var jsonPage = pageData.page;
  var lastPap = pageData.lastPaper;
  $("#results").append('<div class="col-lg-2 col-md-4 col-xs-6 thumb"><a class="thumbnail" href="/paper/' + papBrand + '/read/' + paperId + '" style="text-decoration: none;"><img class="img-responsive" src="http://www.e-pages.dk/' + papBrand + '/' + paperId + '/pic/tm1.jpg" onerror="imageError(this);" alt=""><div style="font-family: Open Sans Condensed; font-weight: 300; position:relative; text-align:center; height: 30px;  width: 100px; left: 42px; top: -125px; background-color: ' + bColor + '; font-size: 20px; opacity: 0.95; color: ' + tColor + '; -webkit-border-radius: 6px; -moz-border-radius: 6px; border-radius: 6px;">' + publishedPrettyDate + '</div></a></div>');
}

var pageFact = (jsonPage < 10 ? 10 : 5);

var paginationWidget = [];

paginationWidget.push('<ul class="pagination" style="padding-left: 30%;">');
paginationWidget.push('<li><a href="#" class="pageResultBut" data-datac="' + (jsonPage-1) + '">«</a></li>');
for (i = (jsonPage < 10 ? (jsonPage < 1 ? 1 : jsonPage) : jsonPage-5); i < (jsonPage+10 > lastPap / 20 ? (lastPap / 20) : jsonPage+pageFact); i++)
{
  paginationWidget.push('<li><a href="#" class="pageResultBut" data-datac="' + i + '">' + i + '</a></li>');
}
paginationWidget.push('<li><a href="#" class="pageResultBut" data-datac="' + (jsonPage+1) + '">»</a></li>');
paginationWidget.push('</ul>');

$("#results").append(paginationWidget.join(""));


    }

  });
}

function loadPage(pageId)
{
	$.ajax({
	method:'post',
    url:'/json_paperpaging.php',
	data: {
        'page': pageId,
        'paper': '<?php echo $brand; ?>'
    },
    success: function (data){

      $("#results").empty();

      var pageData = JSON.parse(JSON.stringify(data));

      for (i = 0; i < pageData.items.length; i++)
      {
        //$("#results").append(pageData.items[i].id);
        var paperId = pageData.items[i].id;
        var publishedDate = new Date(pageData.items[i].published*1000);
        var publishedPrettyDate = publishedDate.getDate() + "." + (publishedDate.getMonth()+1) + "." + publishedDate.getFullYear();
        var papBrand = pageData.paperBrand;
        var bColor = pageData.brandColor;
        var tColor = (bColor == "#ffffff" ? "black" : "white");
        var jsonPage = pageData.page;
        var lastPap = pageData.lastPaper;
        $("#results").append('<div class="col-lg-2 col-md-4 col-xs-6 thumb"><a class="thumbnail" href="/paper/' + papBrand + '/read/' + paperId + '" style="text-decoration: none;"><img class="img-responsive" src="http://www.e-pages.dk/' + papBrand + '/' + paperId + '/pic/tm1.jpg" onerror="imageError(this);" alt=""><div style="font-family: Open Sans Condensed; font-weight: 300; position:relative; text-align:center; height: 30px;  width: 100px; left: 42px; top: -125px; background-color: ' + bColor + '; font-size: 20px; opacity: 0.95; color: ' + tColor + '; -webkit-border-radius: 6px; -moz-border-radius: 6px; border-radius: 6px;">' + publishedPrettyDate + '</div></a></div>');
      }

      var pageFact = (jsonPage < 10 ? 10 : 5);

      var paginationWidget = [];

      paginationWidget.push('<ul class="pagination" style="padding-left: 30%;">');
      paginationWidget.push('<li><a href="#" class="pageResultBut" data-datac="' + (jsonPage-1) + '">«</a></li>');
      for (i = (jsonPage < 10 ? (jsonPage < 1 ? 1 : jsonPage) : jsonPage-5); i < (jsonPage+10 > lastPap / 20 ? (lastPap / 20) : jsonPage+pageFact); i++)
      {
        paginationWidget.push('<li><a href="#" class="pageResultBut" data-datac="' + i + '">' + i + '</a></li>');
      }
      paginationWidget.push('<li><a href="#" class="pageResultBut" data-datac="' + (jsonPage+1) + '">»</a></li>');
      paginationWidget.push('</ul>');

      $("#results").append(paginationWidget.join(""));

    }

  });
}
<?php
}
?>
</script>

  </head>
  <body>

    <div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<nav class="navbar navbar-default navbar-custom" role="navigation">
				<div class="navbar-header">

					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						 <span class="sr-only">Navigaation tila</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
					</button> <a class="navbar-brand" href="#"><div style="background-image: url(/assets/images/logo_lp.png); width: 153px; height: 32px;"></div></a>
				</div>

				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
					<?php
					if (isset($_SESSION["LOGIN_USER"]))
{
	?>
						<li class="active">
							<a href="/">Etusivu</a>
						</li>
						<li>
							<a href="/papers">Lehdet</a>
						</li>
						<?php
						if (@$_SESSION["IS_ADMIN"])
						{
						?>
						<li class="dropdown">
							 <a href="#" class="dropdown-toggle" data-toggle="dropdown">Ylläpito<strong class="caret"></strong></a>
							<ul class="dropdown-menu">
								<li>
									<a href="#">Hae lehdet</a>
								</li>
								<li>
									<a href="#">Lisää uusi lehtikategoria</a>
								</li>
								<li>
									<a href="/administration/users">Käyttäjät</a>
								</li>
								<li class="divider">
								</li>
								<li>
									<a href="/administration/invites">Kutsukoodit</a>
								</li>
								<li class="divider">
								</li>
								<li>
									<a href="#">Tilastointi</a>
								</li>
							</ul>
						</li>
						<?php
						}
						?>
					</ul>

					<ul class="nav navbar-nav navbar-right">

						<li class="dropdown">
							 <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION["LOGIN_USER"]; ?><strong class="caret"></strong></a>
							<ul class="dropdown-menu">

								<li>
									<a href="/edit_profile">Omat tiedot</a>
								</li>

								<li class="divider">
								</li>
								<li>
									<a href="/logout">Kirjaudu ulos</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
				<?php
}
?>
			</nav>
