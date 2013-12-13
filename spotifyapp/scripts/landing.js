require([
  '$api/models',
  'scripts/jquery.min'
], function(models, jquery) {
	$("#user").html("Welcome back " + sessionStorage.username);
	$('#submit').click(function() {
	    event.preventDefault();
		var $inputs = $('#partyNameForm :input');
		var values = {};
		$inputs.each(function() {
		    values[this.name] = $(this).val();
		});
		values["uid"] = sessionStorage.uid;
		values["locale"] = "sv";

		$.post(constants.SERVER_URL + '/api/party/create_party',values, function(data, textstatus)
		{ 
			var json = data;
			if(json.status == "success")
			{
				sessionStorage.partyname = json.result.name;
				window.location.href = "party.html";
			}
			else
			{
				$('#createError').html(json.status + ": " + json.response);
			}
		});
  });
});
