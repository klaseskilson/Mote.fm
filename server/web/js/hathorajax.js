jQuery(document).ready(function($) {	
	var interval = 1000*10*1;
	var ajax_call = function(){
		console.log("sent");
		$.post('./index.php/hathorPost/', "", function(data, textStatus) {
			$("#songdata").html(data);
			ajax_call();
		});
	}
	ajax_call();
	
});