jQuery(document).ready(function($) {
	
	//Recursive AJAJ
	var ajaj_call = function(){

		//debushiet
		var currentdate = new Date(); 
		var datetime = currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();

		console.log("Last Sync sent at: " + datetime);

		//send ajax post, no need to send anything, its all in the session
		$.post('./api/party/get_playing_song', "", function(data, textStatus) {
			console.log(data);
			try
			{
				var music = data;
				$("#songdata").html(music.result.track);
				$("#trackName").html(music.result.trackname);
				$("#artistName").html(music.result.artistname);

				$("#songInfo").fadeOut(500, function() {
	        		$("#songInfo").html( '<img src="' + music.result.albumart  + '">');
	        		$('#songInfo').fadeIn(500);
	    		});
			}
			catch(err)
			{
				console.log("Error parsing data " + err);
			}
			ajaj_call();
		});
	}

	ajaj_call();
	
});