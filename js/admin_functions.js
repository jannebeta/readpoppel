$(document).ready(function(){
    $( ".deleteInvite" ).click(function() {
 var wantRemove = $(this).closest("tr");
  
  var jqxhr = $.post( "/ajax/administration/remove_invite", { id: $(this).attr("data-id") } , function(data) {
    $(wantRemove).hide(500);
	 $( "#notification" ).hide();
	$( "#notification" ).removeClass( "alert-danger" );
	$( "#notification" ).addClass( "alert-success" );
	$( "#notification" ).html(data);
	$( "#notification" ).fadeIn( "slow");
    //alert(data);
})
  .fail(function(xhr, textStatus, errorCode) {
	  $( "#notification" ).hide();
	  	$( "#notification" ).removeClass( "alert-success" );
	$( "#notification" ).addClass( "alert-danger" );
	  $( "#notification" ).html("<strong>Virhe!</strong> Kutsukoodin poistaminen epäonnistui järjestelmävirheen (Virhe: " + errorCode + ") vuoksi. Ole hyvä ja ota yhteyttä kehittäjään <i>lukupoppeli.kehitys@ankanmaa.fi</i>.");
     $( "#notification" ).fadeIn( "slow");
  })
  return false;
});


});
