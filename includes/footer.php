<script>
$(document).ready(function(){
	$("#results").on('mouseenter', '.phraseText', function(){
		
 $(this).css("overflow", "visible");
  $(this).css("height", null);
});
	$("#results").on('mouseleave', '.phraseText', function(){
 $(this).css("overflow", "hidden");
  $(this).css("height", "100px");
});
	$("#results").on('click', '.pageResultBut', function(){

var d = $(this).attr('data-datac');
      loadPage(d);
	  return false;
});
    $('[data-toggle="popover"]').popover();
});
</script>
</body>
</html>