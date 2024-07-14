jQuery(function($){
   $("#inviteCode").mask("******-******-******-******",{completed: function(){CheckCode(this.val());}});
});
function ShowLogin()
{
	 $("#result").empty();
	$("#result").append("Hetkinen..");
	 $( "#invite_box" ).fadeOut( "2000", function() {
             

	  $.post('/ajax/show_login',{}, function(data) {
		  
		  $("#invite_box").fadeIn("3000");
             $("#invite_box").empty();
              $("#invite_box").append(data);
			  
            }).fail(function(xhr, ajaxOptions, thrownError) {
             $("#result").empty();
              $("#result").append("<div class=\"icon_failed\"></div> Kirjautumisikkunaa ei voida ladata.");
	
            });
			  });
}
function LoginToService()
{
	  $.post('/ajax/login',{'username': document.getElementById("login_username").value, 'password': document.getElementById("login_password").value}, function(data) {
             $("#infoArea").empty();
              $("#infoArea").append(data);
			  
            }).fail(function(xhr, ajaxOptions, thrownError) {
             $("#invite_box").empty();
              $("#invite_box").append("<div class=\"icon_failed\"></div> Oijoi! Palautuspyynnön sijaan saimme palvelinvirheen. Ota yhteyttä ylläpitäjään.");
	
            });
}

function ShowPasswordReset()
{
	 $("#infoArea").empty();
	$("#infoArea").append("Ladataan..");
	 $( "#invite_box" ).fadeOut( "2000", function() {
	  $.post('/ajax/password_forgot',{}, function(data) {
		   $("#invite_box").fadeIn("3000");
             $("#invite_box").empty();
              $("#invite_box").append(data);
			  
            }).fail(function(xhr, ajaxOptions, thrownError) {
             $("#infoArea").empty();
              $("#infoArea").append("Salasanapalautus lomakkeen lataaminen epäonnistui palvelinvirheen vuoksi.");
	
            });
			
});
}

function CheckCode()
{

	  $.post('/ajax/check_code',{'code': document.getElementById("inviteCode").value}, function(data) {
			     $("#result").empty();
	 $("#result").append(data);
		   $( "#result" ).hide().fadeIn("slow");

			  
            }).fail(function(xhr, ajaxOptions, thrownError) {
             $("#result").empty();
              $("#result").append("Koodin kelpoisuuden tarkistaminen epäonnistui palvelinvirheen vuoksi.");
	
            });
			

}

function RegisterCode()
{
	 $("#result").empty();
	$("#result").append("Odotahan hetkinen, valmistelemme seuraavaa vaihetta...");
	  $.post('/ajax/redeem_code',{'code': document.getElementById("inviteCode").value}, function(data) {
             $("#invite_box").empty();
              $("#invite_box").append(data);
			  
            }).fail(function(xhr, ajaxOptions, thrownError) {
             $("#result").empty();
              $("#result").append("<div class=\"icon_failed\"></div> Oijoi! Palautuspyynnön sijaan saimme palvelinvirheen. Ota yhteyttä ylläpitäjään.");
	
            });
			

}

function FinishRegistration()
{
	 $("#infoArea").empty();
	$("#infoArea").append("Tarkistamme täyttämiäsi tietoja.. Menee pikku hetki!");
	  $.post('/ajax/finish_registration',{'username': document.getElementById("register_username").value, 'password': document.getElementById("register_password").value, 'password_again': document.getElementById("register_password_again").value, 'email': document.getElementById("register_email").value}, function(data) {
             $("#infoArea").empty();
              $("#infoArea").append(data);
			  
            }).fail(function(xhr, ajaxOptions, thrownError) {
             $("#infoArea").empty();
              $("#infoArea").append("<div class=\"icon_failed\"></div> Voihan vihveli! Rekisteröinti ei onnistunut palvelinvirheen vuoksi.");
	
            });
			

}