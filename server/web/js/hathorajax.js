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
			$(divID).html(data);
			console.log(textStatus);
			ajax_call(divID);
		});
	}

	ajax_call("#songdata");
	
});