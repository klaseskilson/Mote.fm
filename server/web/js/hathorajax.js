jQuery(document).ready(function($) {
	
	//recursive function for ajax requests
	var ajax_call = function(divID){

		//debushiet
		var currentdate = new Date(); 
		var datetime = "Last Sync: " + currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + " @ "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();

		console.log("sent at" + datetime);

		//send ajax post, no need to send anything, its all in the session
		$.post('./index.php/hathorPost/', "", function(data, textStatus) {
			console.log(data);
			try
			{
				var music = eval ("(" + data + ")");
				$("#songdata").html(music.track);
				$("#trackName").html(music.trackname);
				$("#artistName").html(music.artistname);

				$("#songInfo").fadeOut(500, function() {
	        		$("#songInfo").html( '<img src="' + music.trackdata  + '">');
	        		$('#songInfo').fadeIn(500);
	    		});

				console.log(textStatus);
			}
			catch(err)
			{
				console.log("Error parsing data " + err);
			}
			ajax_call(divID);
		});
	}

	ajax_call("#songdata");
	
});