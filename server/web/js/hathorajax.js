jQuery(document).ready(function($) {
	var musicTrack = {
		"partyID" : 123456,
		"trackURI" : ""
	}
	
	$.post('./index.php/hathorPost/', musicTrack, function(data, textStatus) {
		console.log(data);
		console.log(textStatus);
	});
	
});