<?php
$paperBrand = $_POST["paperBrand"];
$paperId = $_POST["paperId"];
$paperPage = $_POST["paperPage"];
$sivu = file_get_contents('http://www.e-pages.dk/' . $paperBrand . '/' . $paperId . '/demo/');
$secretKey = @explode("key4: '", $sivu)[1];
$secretKey = substr($secretKey, 0, 16);
?>
<a href="#close" class="btn btn-primary">Sulje uutislehti</a>
<div id="flashContent">
			<object type="application/x-shockwave-flash" data="/readpage2.swf" width="1152" height="700" id="readpage">
				<param name="movie" value="/readpage2.swf" />
				<param name="quality" value="best" />
				<param name="bgcolor" value="#ffffff" />
				<param name="play" value="true" />
				<param name="loop" value="false" />
				<param name="wmode" value="window" />
				<param name="scale" value="noborder" />
				<param name="flashvars" value="paperBrand=<?php echo $paperBrand; ?>&amp;paperId=<?php echo $paperId; ?>&amp;paperPage=<?php echo $paperPage; ?>&amp;serverKey=<?php echo $secretKey; ?>" />
				<param name="devicefont" value="false" />
				<param name="salign" value="" />
				<param name="allowScriptAccess" value="sameDomain" />
				<a href="http://www.adobe.com/go/getflash">
					<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
				</a>
			</object>
		</div>